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
Route::post('/contact', [ContactController::class, 'send'])
    ->middleware('throttle:contact-form')
    ->name('contact.send');

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index']);
Route::get('/robots.txt', [\App\Http\Controllers\RobotsController::class, 'index']);
Route::get('/lang/{locale}', [\App\Http\Controllers\OpsController::class, 'switchLocale'])->name('lang.switch');

// Protected maintenance & web-cron endpoints (token/auth enforced in OpsController)
Route::get('/setup-migrate', [\App\Http\Controllers\OpsController::class, 'setupMigrate'])->name('ops.setup-migrate');
Route::get('/cron/schedule-run', [\App\Http\Controllers\OpsController::class, 'cronScheduleRun'])->name('cron.schedule-run');
Route::get('/setup-db', [\App\Http\Controllers\OpsController::class, 'setupDb'])->name('ops.setup-db');
Route::get('/setup-storage', [\App\Http\Controllers\OpsController::class, 'setupStorage'])->name('ops.setup-storage');

// Fallback direct storage route if symlink is missing on hosting
Route::get('/storage/{path}', [\App\Http\Controllers\OpsController::class, 'serveStorage'])->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth (guest only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:auth-strict')
            ->name('login.post');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::delete('/profile/tokens/{token}', [App\Http\Controllers\Admin\ProfileController::class, 'destroyToken'])->name('profile.tokens.destroy');
        Route::resource('projects', App\Http\Controllers\Admin\ProjectController::class)->except(['show']);
        Route::post('technologies/quick', [App\Http\Controllers\Admin\TechnologyController::class, 'quick'])->name('technologies.quick');
        Route::resource('technologies', App\Http\Controllers\Admin\TechnologyController::class)->except(['show']);
        Route::post('blog/{blog}/generate-image', [App\Http\Controllers\Admin\BlogController::class, 'generateImage'])->name('blog.generate-image');
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
            Route::post('providers/fetch-models', [App\Http\Controllers\Admin\AIProviderController::class, 'fetchModels'])->name('providers.fetch-models');
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
            Route::post('posts/{post}/generate-image', [App\Http\Controllers\Admin\AIPostController::class, 'generateImage'])->name('posts.generate-image');
            Route::delete('posts/{post}', [App\Http\Controllers\Admin\AIPostController::class, 'destroy'])->name('posts.destroy');

            // Logs
            Route::get('logs', [App\Http\Controllers\Admin\BlogAutomationLogController::class, 'index'])->name('logs.index');
            Route::get('logs/{log}', [App\Http\Controllers\Admin\BlogAutomationLogController::class, 'show'])->name('logs.show');
            Route::post('logs/{log}/retry', [App\Http\Controllers\Admin\BlogAutomationLogController::class, 'retry'])->name('logs.retry');
        });
    });
});
