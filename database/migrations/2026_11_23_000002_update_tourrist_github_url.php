<?php

use App\Support\PortfolioCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('projects') || !Schema::hasColumn('projects', 'github_url')) {
            return;
        }

        $targetUrl = 'https://github.com/hoThanhThien/RAG';
        $rows = DB::table('projects')
            ->whereRaw('LOWER(title) LIKE ?', ['%tourrist%'])
            ->get(['id', 'github_url']);

        foreach ($rows as $row) {
            $current = trim((string) ($row->github_url ?? ''));
            $lower = rtrim(strtolower($current), '/');

            if (
                $lower === ''
                || $lower === 'https://github.com/hothanhthien'
                || str_ends_with($lower, '/tourrist')
            ) {
                DB::table('projects')
                    ->where('id', $row->id)
                    ->update(['github_url' => $targetUrl]);
            }
        }

        if (class_exists(PortfolioCache::class)) {
            PortfolioCache::forgetAll();
        }
    }

    public function down(): void
    {
        // Non-destructive data backfill
    }
};
