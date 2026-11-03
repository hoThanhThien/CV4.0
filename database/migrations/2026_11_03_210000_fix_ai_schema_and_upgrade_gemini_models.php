<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Heal any missing columns on blog_posts if a previous migration partially failed on MySQL
        if (Schema::hasTable('blog_posts')) {
            $columnsToAdd = [
                'status' => fn (Blueprint $t) => $t->string('status', 50)->default('draft'),
                'ai_generated' => fn (Blueprint $t) => $t->boolean('ai_generated')->default(false),
                'ai_provider_id' => fn (Blueprint $t) => $t->unsignedBigInteger('ai_provider_id')->nullable()->index(),
                'ai_config_id' => fn (Blueprint $t) => $t->unsignedBigInteger('ai_config_id')->nullable()->index(),
                'meta_title' => fn (Blueprint $t) => $t->string('meta_title', 255)->nullable(),
                'meta_description' => fn (Blueprint $t) => $t->text('meta_description')->nullable(),
                'keywords' => fn (Blueprint $t) => $t->text('keywords')->nullable(),
                'category' => fn (Blueprint $t) => $t->string('category', 100)->nullable(),
                'tags' => fn (Blueprint $t) => $t->text('tags')->nullable(),
                'faq' => fn (Blueprint $t) => $t->text('faq')->nullable(),
                'sources' => fn (Blueprint $t) => $t->text('sources')->nullable(),
                'cover_image_prompt' => fn (Blueprint $t) => $t->text('cover_image_prompt')->nullable(),
                'scheduled_at' => fn (Blueprint $t) => $t->timestamp('scheduled_at')->nullable(),
                'last_edited_by_admin' => fn (Blueprint $t) => $t->boolean('last_edited_by_admin')->default(false),
            ];

            foreach ($columnsToAdd as $col => $callback) {
                if (!Schema::hasColumn('blog_posts', $col)) {
                    Schema::table('blog_posts', function (Blueprint $table) use ($callback) {
                        $callback($table);
                    });
                }
            }

            // Ensure manual published posts have status = 'published'
            DB::table('blog_posts')
                ->where('published', true)
                ->where('status', '!=', 'published')
                ->update(['status' => 'published']);
        }

        // 2. Automatically upgrade retired Google Gemini 1.5 / 2.0 / 2.5 models on existing providers to active 3.5 Flash
        if (Schema::hasTable('ai_providers')) {
            DB::table('ai_providers')
                ->where('provider_type', 'gemini')
                ->whereIn('model', [
                    'gemini-1.5-flash',
                    'gemini-1.5-flash-latest',
                    'gemini-1.5-flash-001',
                    'gemini-1.5-flash-002',
                    'gemini-1.5-flash-8b',
                    'gemini-2.0-flash',
                    'gemini-2.0-flash-exp',
                    'gemini-2.5-flash',
                    'gemini-1.5-pro',
                    'gemini-1.5-pro-latest',
                    'gemini-1.5-pro-001',
                    'gemini-1.5-pro-002',
                    'gemini-pro',
                    'models/gemini-1.5-flash',
                    'models/gemini-1.5-pro',
                ])
                ->update([
                    'model' => 'gemini-3.5-flash',
                ]);

            // Ensure Gemini providers have sufficient max_tokens (>= 8192) for full JSON + thinking output
            DB::table('ai_providers')
                ->where('provider_type', 'gemini')
                ->where('max_tokens', '<', 8192)
                ->update([
                    'max_tokens' => 8192,
                ]);
        }
    }

    public function down()
    {
        // Non-destructive healing migration
    }
};

