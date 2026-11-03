<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('blog_posts')) {
            return;
        }

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

        // Sync existing records: if published is true, set status = 'published'
        DB::table('blog_posts')->where('published', true)->update([
            'status' => 'published',
        ]);
    }

    public function down()
    {
        if (!Schema::hasTable('blog_posts')) {
            return;
        }

        $cols = [
            'status',
            'ai_generated',
            'ai_provider_id',
            'ai_config_id',
            'meta_title',
            'meta_description',
            'keywords',
            'category',
            'tags',
            'faq',
            'sources',
            'cover_image_prompt',
            'scheduled_at',
            'last_edited_by_admin',
        ];

        foreach ($cols as $col) {
            if (Schema::hasColumn('blog_posts', $col)) {
                Schema::table('blog_posts', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }
    }
};
