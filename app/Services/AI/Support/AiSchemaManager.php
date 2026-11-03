<?php

namespace App\Services\AI\Support;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AiSchemaManager
{
    protected static ?bool $readyInMemory = null;
    protected static ?bool $hasStatusColInMemory = null;

    /**
     * Reset in-memory flags (useful in tests or right after running migrations).
     */
    public static function flushCache(): void
    {
        self::$readyInMemory = null;
        self::$hasStatusColInMemory = null;
        try {
            Cache::forget('ai_schema_ready_v2');
            Cache::forget('blog_posts_has_status_col_v2');
        } catch (Throwable $e) {
            // Ignore cache errors
        }
    }

    /**
     * Check whether blog_posts.status column exists (safe for public routes prior to migration).
     */
    public static function hasBlogStatusColumn(): bool
    {
        if (app()->runningUnitTests()) {
            try {
                return Schema::hasTable('blog_posts') && Schema::hasColumn('blog_posts', 'status');
            } catch (Throwable $e) {
                return false;
            }
        }

        if (self::$hasStatusColInMemory !== null) {
            return self::$hasStatusColInMemory;
        }

        try {
            if (Cache::get('blog_posts_has_status_col_v2') === true) {
                return self::$hasStatusColInMemory = true;
            }

            $exists = Schema::hasTable('blog_posts') && Schema::hasColumn('blog_posts', 'status');
            if ($exists) {
                Cache::put('blog_posts_has_status_col_v2', true, 86400);
            }

            return self::$hasStatusColInMemory = $exists;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Check whether all AI Blog Studio database tables & columns are ready.
     */
    public static function isReady(): bool
    {
        if (app()->runningUnitTests()) {
            return self::checkDatabaseSchema();
        }

        if (self::$readyInMemory !== null) {
            return self::$readyInMemory;
        }

        try {
            if (Cache::get('ai_schema_ready_v2') === true) {
                return self::$readyInMemory = true;
            }

            $ready = self::checkDatabaseSchema();
            if ($ready) {
                Cache::put('ai_schema_ready_v2', true, 86400);
                Cache::put('blog_posts_has_status_col_v2', true, 86400);
            }

            return self::$readyInMemory = $ready;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Automatically run pending migrations if AI tables/columns are missing.
     */
    public static function ensureMigrated(): array
    {
        if (self::isReady()) {
            return ['ready' => true, 'migrated' => false, 'error' => null];
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
            self::flushCache();

            $ready = self::isReady();
            return [
                'ready' => $ready,
                'migrated' => true,
                'output' => trim(Artisan::output()),
                'error' => $ready ? null : 'Đã chạy migrate nhưng một số bảng AI vẫn chưa sẵn sàng.',
            ];
        } catch (Throwable $e) {
            return [
                'ready' => false,
                'migrated' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    protected static function checkDatabaseSchema(): bool
    {
        try {
            return Schema::hasTable('ai_providers')
                && Schema::hasTable('ai_content_configs')
                && Schema::hasTable('blog_automations')
                && Schema::hasTable('blog_automation_runs')
                && Schema::hasTable('blog_posts')
                && Schema::hasColumn('blog_posts', 'status')
                && Schema::hasColumn('blog_posts', 'ai_generated');
        } catch (Throwable $e) {
            return false;
        }
    }
}

