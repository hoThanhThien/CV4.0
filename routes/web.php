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
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::post('/contact/verify-recaptcha', [ContactController::class, 'verifyRecaptcha'])->name('contact.verify-recaptcha');
Route::get('/contact/captcha', [ContactController::class, 'captcha'])->name('contact.captcha');

Route::get('/sitemap.xml', function () {
    $projects = \App\Models\Project::all();
    $posts = \App\Models\BlogPost::published()->get();

    $staticPages = [
        ['url' => url('/'), 'freq' => 'weekly', 'priority' => '1.0'],
        ['url' => url('/about'), 'freq' => 'monthly', 'priority' => '0.8'],
        ['url' => url('/contact'), 'freq' => 'monthly', 'priority' => '0.9'],
        ['url' => url('/projects'), 'freq' => 'weekly', 'priority' => '0.9'],
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
    $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /admin/\nDisallow: /setup-db\nDisallow: /setup-db/\n\nSitemap: {$sitemapUrl}\n";
    return response($content, 200, ['Content-Type' => 'text/plain']);
});
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'vi'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/setup-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return 'Database migrated and seeded successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

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
        Route::resource('blog', App\Http\Controllers\Admin\BlogController::class);
        Route::resource('skills', App\Http\Controllers\Admin\SkillController::class)->except(['show']);
        Route::resource('experiences', App\Http\Controllers\Admin\ExperienceController::class)->except(['show']);
    });
});
