<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/certificates', [PageController::class, 'certificates'])->name('certificates');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/sitemap.xml', function () {
    $projects = \App\Models\Project::all();
    $posts = \App\Models\BlogPost::published()->get();

    $staticPages = [
        ['url' => url('/'), 'freq' => 'weekly', 'priority' => '1.0'],
        ['url' => url('/about'), 'freq' => 'monthly', 'priority' => '0.8'],
        ['url' => url('/contact'), 'freq' => 'monthly', 'priority' => '0.9'],
        ['url' => url('/projects'), 'freq' => 'weekly', 'priority' => '0.9'],
        ['url' => url('/certificates'), 'freq' => 'weekly', 'priority' => '0.85'],
        ['url' => url('/blog'), 'freq' => 'weekly', 'priority' => '0.8'],
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

    // Static pages with bilingual alternates
    foreach ($staticPages as $page) {
        foreach (['vi', 'en'] as $lang) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($page['url'] . '?lang=' . $lang, ENT_XML1) . "</loc>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"vi\" href=\"" . htmlspecialchars($page['url'] . '?lang=vi', ENT_XML1) . "\"/>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"" . htmlspecialchars($page['url'] . '?lang=en', ENT_XML1) . "\"/>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"" . htmlspecialchars($page['url'], ENT_XML1) . "\"/>\n";
            $xml .= "    <lastmod>" . now()->toAtomString() . "</lastmod>\n";
            $xml .= "    <changefreq>{$page['freq']}</changefreq>\n";
            $xml .= "    <priority>{$page['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }
    }

    // Dynamic project pages
    foreach ($projects as $project) {
        $pUrl = url('/projects/' . $project->id);
        $lastmod = $project->updated_at ? $project->updated_at->toAtomString() : now()->toAtomString();
        foreach (['vi', 'en'] as $lang) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($pUrl . '?lang=' . $lang, ENT_XML1) . "</loc>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"vi\" href=\"" . htmlspecialchars($pUrl . '?lang=vi', ENT_XML1) . "\"/>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"" . htmlspecialchars($pUrl . '?lang=en', ENT_XML1) . "\"/>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"" . htmlspecialchars($pUrl, ENT_XML1) . "\"/>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }
    }

    // Dynamic blog posts
    foreach ($posts as $post) {
        $bUrl = url('/blog/' . $post->slug);
        $lastmod = $post->updated_at ? $post->updated_at->toAtomString() : now()->toAtomString();
        foreach (['vi', 'en'] as $lang) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($bUrl . '?lang=' . $lang, ENT_XML1) . "</loc>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"vi\" href=\"" . htmlspecialchars($bUrl . '?lang=vi', ENT_XML1) . "\"/>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"" . htmlspecialchars($bUrl . '?lang=en', ENT_XML1) . "\"/>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"" . htmlspecialchars($bUrl, ENT_XML1) . "\"/>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }
    }

    $xml .= '</urlset>';

    return response($xml, 200, [
        'Content-Type' => 'text/xml; charset=utf-8'
    ]);
});

Route::get('/robots.txt', function () {
    $sitemapUrl = url('/sitemap.xml');
    $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /admin/\nDisallow: /setup-db\nDisallow: /setup-db/\nDisallow: /setup-migrate\nDisallow: /cron/\n\nSitemap: {$sitemapUrl}\n";
    return response($content, 200, ['Content-Type' => 'text/plain']);
});
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'vi'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/setup-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \App\Services\AI\Support\AiSchemaManager::flushCache();
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return response()->json([
            'status' => 'ok',
            'message' => 'Database migrated and caches cleared successfully!',
            'ai_schema_ready' => \App\Services\AI\Support\AiSchemaManager::isReady(),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});

// Web-cron endpoint for cPanel Cron Jobs / GitHub Actions / Uptime monitors
Route::get('/cron/schedule-run', function (\Illuminate\Http\Request $request) {
    $configuredSecret = env('CRON_SECRET');
    if (!empty($configuredSecret) && !hash_equals((string) $configuredSecret, (string) $request->query('token', ''))) {
        abort(403, 'Invalid cron token.');
    }

    @set_time_limit(300);
    @ignore_user_abort(true);

    try {
        if (!\App\Services\AI\Support\AiSchemaManager::isReady()) {
            \App\Services\AI\Support\AiSchemaManager::ensureMigrated();
        }

        \Illuminate\Support\Facades\Artisan::call('blog:publish-scheduled');
        $publishOutput = trim(\Illuminate\Support\Facades\Artisan::output());

        \Illuminate\Support\Facades\Artisan::call('blog:run-automations');
        $automationOutput = trim(\Illuminate\Support\Facades\Artisan::output());

        return response()->json([
            'status' => 'ok',
            'timestamp_utc' => now()->toIso8601String(),
            'timestamp_vn' => now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
            'publish_scheduled' => $publishOutput,
            'run_automations' => $automationOutput,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
})->name('cron.schedule-run');

Route::get('/setup-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'Database migrated, seeded, and storage linked successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/setup-storage', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'Storage symlink created successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Fallback direct storage route if symlink is missing on hosting
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth (guest only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('projects', App\Http\Controllers\Admin\ProjectController::class)->except(['show']);
        Route::post('technologies/quick', [App\Http\Controllers\Admin\TechnologyController::class, 'quick'])->name('technologies.quick');
        Route::resource('technologies', App\Http\Controllers\Admin\TechnologyController::class)->except(['show']);
        Route::resource('blog', App\Http\Controllers\Admin\BlogController::class);
        Route::post('skills/quick', [App\Http\Controllers\Admin\SkillController::class, 'quick'])->name('skills.quick');
        Route::resource('skills', App\Http\Controllers\Admin\SkillController::class)->except(['show']);
        Route::resource('experiences', App\Http\Controllers\Admin\ExperienceController::class)->except(['show']);
        Route::resource('certificates', App\Http\Controllers\Admin\CertificateController::class)->except(['show']);
        Route::resource('messages', App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'destroy']);
        Route::post('messages/{message}/toggle-read', [App\Http\Controllers\Admin\ContactMessageController::class, 'toggleRead'])->name('messages.toggle-read');

        // AI Blog Studio
        Route::prefix('ai')->name('ai.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AIDashboardController::class, 'index'])->name('dashboard');

            // Providers
            Route::post('providers/{provider}/test', [App\Http\Controllers\Admin\AIProviderController::class, 'testConnection'])->name('providers.test');
            Route::post('providers/{provider}/set-default', [App\Http\Controllers\Admin\AIProviderController::class, 'setDefault'])->name('providers.set-default');
            Route::post('providers/{provider}/toggle-active', [App\Http\Controllers\Admin\AIProviderController::class, 'toggleActive'])->name('providers.toggle-active');
            Route::resource('providers', App\Http\Controllers\Admin\AIProviderController::class)->except(['show']);

            // Content Configurations
            Route::post('configs/{config}/generate-now', [App\Http\Controllers\Admin\AIContentConfigController::class, 'generateNow'])->name('configs.generate-now');
            Route::resource('configs', App\Http\Controllers\Admin\AIContentConfigController::class)->except(['show']);

            // Automations
            Route::post('automations/trigger-due', [App\Http\Controllers\Admin\BlogAutomationController::class, 'triggerDueNow'])->name('automations.trigger-due');
            Route::post('automations/{automation}/toggle-active', [App\Http\Controllers\Admin\BlogAutomationController::class, 'toggleActive'])->name('automations.toggle-active');
            Route::post('automations/{automation}/run-now', [App\Http\Controllers\Admin\BlogAutomationController::class, 'runNow'])->name('automations.run-now');
            Route::resource('automations', App\Http\Controllers\Admin\BlogAutomationController::class)->except(['show']);

            // AI Generated Posts
            Route::get('posts', [App\Http\Controllers\Admin\AIPostController::class, 'index'])->name('posts.index');
            Route::get('posts/{post}/preview', [App\Http\Controllers\Admin\AIPostController::class, 'preview'])->name('posts.preview');
            Route::get('posts/{post}/edit', [App\Http\Controllers\Admin\AIPostController::class, 'edit'])->name('posts.edit');
            Route::put('posts/{post}', [App\Http\Controllers\Admin\AIPostController::class, 'update'])->name('posts.update');
            Route::post('posts/{post}/approve', [App\Http\Controllers\Admin\AIPostController::class, 'approve'])->name('posts.approve');
            Route::post('posts/{post}/publish-now', [App\Http\Controllers\Admin\AIPostController::class, 'publishNow'])->name('posts.publish-now');
            Route::post('posts/{post}/schedule', [App\Http\Controllers\Admin\AIPostController::class, 'schedule'])->name('posts.schedule');
            Route::post('posts/{post}/cancel-schedule', [App\Http\Controllers\Admin\AIPostController::class, 'cancelSchedule'])->name('posts.cancel-schedule');
            Route::post('posts/{post}/regenerate', [App\Http\Controllers\Admin\AIPostController::class, 'regenerate'])->name('posts.regenerate');
            Route::delete('posts/{post}', [App\Http\Controllers\Admin\AIPostController::class, 'destroy'])->name('posts.destroy');

            // Logs
            Route::get('logs', [App\Http\Controllers\Admin\BlogAutomationLogController::class, 'index'])->name('logs.index');
            Route::get('logs/{log}', [App\Http\Controllers\Admin\BlogAutomationLogController::class, 'show'])->name('logs.show');
            Route::post('logs/{log}/retry', [App\Http\Controllers\Admin\BlogAutomationLogController::class, 'retry'])->name('logs.retry');
        });
    });
});
