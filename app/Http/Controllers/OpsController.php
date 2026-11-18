<?php

namespace App\Http\Controllers;

use App\Services\AI\Support\AiSchemaManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

/**
 * Operational maintenance and web-cron endpoints protected by token/auth checks.
 */
class OpsController extends Controller
{
    /**
     * Check whether the request supplies the configured cron/ops secret token.
     */
    private function hasValidCronToken(Request $request, ?string $configuredSecret = null): bool
    {
        $configuredSecret ??= (string) config('services.cron.secret', '');
        if ($configuredSecret === '') {
            return false;
        }

        $providedToken = (string) ($request->header('X-Cron-Token') ?: $request->query('token', ''));

        return $providedToken !== '' && hash_equals($configuredSecret, $providedToken);
    }

    /**
     * Guard one-time setup/migration endpoints against anonymous access outside local/testing.
     */
    private function authorizeSetupEndpoint(Request $request): void
    {
        if (app()->environment(['local', 'testing'])) {
            return;
        }

        if (Auth::check() || $this->hasValidCronToken($request)) {
            return;
        }

        abort(403, 'Unauthorized setup endpoint access.');
    }

    /**
     * Run due scheduled blog publishing and AI automations for external cron pings.
     */
    public function cronScheduleRun(Request $request)
    {
        $configuredSecret = (string) config('services.cron.secret', '');
        if ($configuredSecret !== '' && !Auth::check() && !$this->hasValidCronToken($request, $configuredSecret)) {
            abort(403, 'Invalid cron token.');
        }

        @set_time_limit(300);
        @ignore_user_abort(true);

        try {
            if (!AiSchemaManager::isReady()) {
                AiSchemaManager::ensureMigrated();
            }

            Artisan::call('blog:publish-scheduled');
            $publishOutput = trim(Artisan::output());

            Artisan::call('blog:run-automations');
            $automationOutput = trim(Artisan::output());

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
    }

    /**
     * Run idempotent migrations and clear framework caches.
     */
    public function setupMigrate(Request $request)
    {
        $this->authorizeSetupEndpoint($request);

        try {
            Artisan::call('migrate', ['--force' => true]);
            AiSchemaManager::flushCache();
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return response()->json([
                'status' => 'ok',
                'message' => 'Database migrated and caches cleared successfully!',
                'ai_schema_ready' => AiSchemaManager::isReady(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Run initial migrations, seeds, and storage link for fresh hosting setup.
     */
    public function setupDb(Request $request)
    {
        $this->authorizeSetupEndpoint($request);

        try {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
            Artisan::call('storage:link');

            return 'Database migrated, seeded, and storage linked successfully!';
        } catch (\Throwable $e) {
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Create public/storage symlink if missing.
     */
    public function setupStorage(Request $request)
    {
        $this->authorizeSetupEndpoint($request);

        try {
            Artisan::call('storage:link');

            return 'Storage symlink created successfully!';
        } catch (\Throwable $e) {
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Switch session UI locale (`en` | `vi`).
     */
    public function switchLocale(string $locale)
    {
        if (in_array($locale, ['en', 'vi'], true)) {
            session()->put('locale', $locale);
        }

        return redirect()->back();
    }

    /**
     * Fallback handler serving files under public storage when hosting lacks symlinks.
     */
    public function serveStorage(string $path)
    {
        if (str_contains($path, '..')) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . ltrim($path, '/'));
        if (!file_exists($filePath) || !is_file($filePath)) {
            abort(404);
        }

        $headers = [];
        if (str_ends_with(strtolower($filePath), '.svg')) {
            $headers['Content-Type'] = 'image/svg+xml';
        }

        return response()->file($filePath, $headers);
    }
}
