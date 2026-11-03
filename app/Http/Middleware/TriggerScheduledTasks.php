<?php

namespace App\Http\Middleware;

use App\Models\BlogAutomation;
use App\Models\BlogPost;
use App\Services\AI\Support\AiSchemaManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class TriggerScheduledTasks
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Web-cron fallback: runs after the HTTP response is sent to the browser
     * so scheduled posts and AI automations execute reliably even on shared
     * hosting without an OS-level crontab.
     */
    public function terminate($request, $response): void
    {
        if (app()->runningUnitTests() || app()->environment('testing')) {
            return;
        }

        // Skip asset/storage/cron endpoints
        if ($request->is('storage/*', 'cron/*', 'setup-*')) {
            return;
        }

        try {
            // Throttle check to at most once every 55 seconds across all web traffic
            if (!Cache::add('ai_web_cron_last_tick', now()->timestamp, 55)) {
                return;
            }

            if (!AiSchemaManager::isReady()) {
                return;
            }

            $hasDuePosts = BlogPost::dueForPublishing()->exists();
            $hasDueAutomations = BlogAutomation::dueForRun()->exists();

            if (!$hasDuePosts && !$hasDueAutomations) {
                return;
            }

            // Flush response to client first if supported by PHP-FPM / LiteSpeed
            if (function_exists('fastcgi_finish_request')) {
                @fastcgi_finish_request();
            }
            @ignore_user_abort(true);
            @set_time_limit(300);

            if ($hasDuePosts) {
                Artisan::call('blog:publish-scheduled');
            }

            if ($hasDueAutomations) {
                Artisan::call('blog:run-automations');
            }
        } catch (Throwable $e) {
            Log::warning('Web-cron scheduler fallback error: ' . $e->getMessage());
        }
    }
}

