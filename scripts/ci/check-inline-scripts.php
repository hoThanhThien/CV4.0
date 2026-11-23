<?php

/**
 * CI Quality Gate: Renders all public (EN/VI) and admin routes, checks Pearl White
 * design tokens, verifies no raw Blade/PHP errors or legacy FA classes on public pages,
 * validates JSON-LD, and syntax-checks every inline <script> block via `node --check`.
 */

$root = dirname(__DIR__, 2);
$args = array_slice($argv, 1);
$useLiveDb = in_array('--live-db', $args, true);

$tempDbPath = null;
if (!$useLiveDb) {
    $tempDbPath = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'cv_ci_inline_' . getmypid() . '_' . uniqid('', true) . '.sqlite';
    touch($tempDbPath);

    foreach ([
        'APP_ENV' => 'testing',
        'DB_CONNECTION' => 'sqlite',
        'DB_DATABASE' => $tempDbPath,
        'CACHE_DRIVER' => 'array',
        'SESSION_DRIVER' => 'array',
        'QUEUE_CONNECTION' => 'sync',
        'MAIL_MAILER' => 'array',
    ] as $k => $v) {
        putenv("{$k}={$v}");
        $_ENV[$k] = $v;
        $_SERVER[$k] = $v;
    }
}

require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$console = $app->make(Illuminate\Contracts\Console\Kernel::class);
$console->bootstrap();

$jsTmpDir = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'cv_ci_js_' . getmypid();
if (!is_dir($jsTmpDir)) {
    mkdir($jsTmpDir, 0777, true);
}

$cleanup = function () use (&$tempDbPath, $jsTmpDir): void {
    foreach (glob($jsTmpDir . DIRECTORY_SEPARATOR . '*.js') ?: [] as $f) {
        @unlink($f);
    }
    @rmdir($jsTmpDir);
    if ($tempDbPath && is_file($tempDbPath)) {
        @unlink($tempDbPath);
    }
};

try {
    if (!$useLiveDb) {
        $console->call('migrate', ['--force' => true]);
        $console->call('db:seed', ['--force' => true]);
    }

    $admin = App\Models\User::first();
    if (!$admin) {
        $admin = App\Models\User::create([
            'name' => 'CI Admin',
            'email' => 'ci-admin@portfolio.test',
            'password' => Illuminate\Support\Facades\Hash::make('CiSecret123!'),
        ]);
    }

    $publicRoutes = [
        '/',
        '/projects',
        '/projects/1',
        '/projects/2',
        '/projects/3',
        '/projects/4',
        '/blog',
        '/blog/building-document-based-rag-systems',
        '/blog/building-restful-apis-with-spring-boot',
        '/blog/mastering-react-hooks',
        '/about',
        '/certificates',
        '/certificates?category=brochure',
        '/certificates?category=degree',
        '/certificates?category=award',
        '/contact',
    ];

    $adminRoutes = [
        '/admin',
        '/admin/profile',
        '/admin/projects',
        '/admin/blog',
        '/admin/skills',
        '/admin/experiences',
        '/admin/certificates',
        '/admin/messages',
        '/admin/ai',
        '/admin/ai/posts',
        '/admin/ai/automations',
        '/admin/ai/configs',
        '/admin/ai/providers',
        '/admin/ai/logs',
    ];

    $failures = [];
    $checkedPages = 0;
    $checkedScripts = 0;

    $inspectScripts = function (string $label, string $html) use ($jsTmpDir, &$failures, &$checkedScripts): void {
        if (!preg_match_all('/<script\b([^>]*)>(.*?)<\/script>/is', $html, $matches, PREG_SET_ORDER)) {
            return;
        }
        foreach ($matches as $idx => $match) {
            $attrs = $match[1];
            $code = trim($match[2]);
            if ($code === '' || preg_match('/\bsrc\s*=/i', $attrs)) {
                continue;
            }
            if (preg_match('/application\/ld\+json/i', $attrs)) {
                json_decode($code);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $failures[] = "[$label] Invalid JSON-LD block #$idx: " . json_last_error_msg();
                }
                continue;
            }
            $checkedScripts++;
            $jsFile = $jsTmpDir . DIRECTORY_SEPARATOR . "inline_{$checkedScripts}.js";
            file_put_contents($jsFile, $code);
            $out = [];
            $exitCode = 0;
            exec('node --check ' . escapeshellarg($jsFile) . ' 2>&1', $out, $exitCode);
            if ($exitCode !== 0) {
                $failures[] = "[$label] JS syntax error in inline <script> #$idx: " . implode(' | ', $out);
            }
        }
    };

    // 1. Public Routes (EN & VI)
    foreach (['en', 'vi'] as $locale) {
        foreach ($publicRoutes as $path) {
            $sep = str_contains($path, '?') ? '&' : '?';
            $uri = $path . $sep . 'lang=' . $locale;
            $request = Illuminate\Http\Request::create($uri, 'GET');
            $response = $kernel->handle($request);
            $status = $response->getStatusCode();
            $html = (string) $response->getContent();
            $kernel->terminate($request, $response);
            $checkedPages++;

            if ($status !== 200) {
                $failures[] = "[$uri] HTTP status $status";
                continue;
            }
            if (!mb_check_encoding($html, 'UTF-8')) {
                $failures[] = "[$uri] Invalid UTF-8";
            }
            foreach (['bg-tech-grid', 'frosted-glass', 'spotlight-card', 'themeToggleBtn', 'lucide'] as $needle) {
                if (!str_contains($html, $needle)) {
                    $failures[] = "[$uri] Missing Pearl token: $needle";
                }
            }
            if (preg_match('/class="[^"]*\b(fas|fab|far)\s+fa-/i', $html, $m)) {
                $failures[] = "[$uri] Found legacy FontAwesome class: {$m[0]}";
            }
            if (str_contains($html, 'Fatal error') || str_contains($html, 'ParseError')) {
                $failures[] = "[$uri] Found PHP error output";
            }
            if (preg_match('/@section\(|@extends\(|@yield\(/', $html)) {
                $failures[] = "[$uri] Found unparsed Blade directive";
            }
            if ($locale === 'en') {
                $inspectScripts($uri, $html);
            }
        }
    }

    // 2. Guest Admin Login
    $loginReq = Illuminate\Http\Request::create('/admin/login', 'GET');
    $loginRes = $kernel->handle($loginReq);
    $loginHtml = (string) $loginRes->getContent();
    $checkedPages++;
    if ($loginRes->getStatusCode() !== 200) {
        $failures[] = '[/admin/login] HTTP status ' . $loginRes->getStatusCode();
    } else {
        foreach (['lucide-admin.js', 'thien_theme', 'loginThemeToggle'] as $token) {
            if (!str_contains($loginHtml, $token)) {
                $failures[] = "[/admin/login] Missing Pearl token: $token";
            }
        }
        $inspectScripts('/admin/login', $loginHtml);
    }
    $kernel->terminate($loginReq, $loginRes);

    // 3. Authenticated Admin Studio Routes
    foreach ($adminRoutes as $adminPath) {
        $app['auth']->guard('web')->setUser($admin);
        $req = Illuminate\Http\Request::create($adminPath, 'GET');
        $req->setUserResolver(fn () => $admin);
        $res = $kernel->handle($req);
        $status = $res->getStatusCode();
        $html = (string) $res->getContent();
        $kernel->terminate($req, $res);
        $checkedPages++;

        if ($status !== 200) {
            $failures[] = "[$adminPath] HTTP status $status";
            continue;
        }
        if (!mb_check_encoding($html, 'UTF-8')) {
            $failures[] = "[$adminPath] Invalid UTF-8";
        }
        foreach (['lucide-admin.js', 'thien_theme', 'adminThemeToggle'] as $token) {
            if (!str_contains($html, $token)) {
                $failures[] = "[$adminPath] Missing Pearl Admin token: $token";
            }
        }
        if (str_contains($html, 'Fatal error') || str_contains($html, 'ParseError')) {
            $failures[] = "[$adminPath] Found PHP error output";
        }
        if (preg_match('/@section\(|@extends\(|@yield\(/', $html)) {
            $failures[] = "[$adminPath] Found unparsed Blade directive";
        }
        $inspectScripts($adminPath, $html);
    }

    echo "Checked {$checkedPages} renders and {$checkedScripts} inline <script> blocks via node --check.\n";

    if (!empty($failures)) {
        echo "\nFAILURES (" . count($failures) . "):\n";
        foreach ($failures as $f) {
            echo " - {$f}\n";
        }
        exit(1);
    }

    echo "ALL INLINE SCRIPT & ROUTE CHECKS PASSED!\n";
} finally {
    $cleanup();
}
