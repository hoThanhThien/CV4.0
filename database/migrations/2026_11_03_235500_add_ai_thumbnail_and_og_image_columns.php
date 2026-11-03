<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('blog_posts') && !Schema::hasColumn('blog_posts', 'og_image')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->string('og_image')->nullable()->after('image');
            });
        }

        if (Schema::hasTable('ai_content_configs')) {
            Schema::table('ai_content_configs', function (Blueprint $table) {
                if (!Schema::hasColumn('ai_content_configs', 'image_model')) {
                    $table->string('image_model')->nullable()->after('model_override');
                }
                if (!Schema::hasColumn('ai_content_configs', 'auto_generate_image')) {
                    $table->boolean('auto_generate_image')->default(true)->after('include_sources');
                }
                if (!Schema::hasColumn('ai_content_configs', 'image_style')) {
                    $table->string('image_style', 60)->default('modern_tech')->after('auto_generate_image');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('blog_posts') && Schema::hasColumn('blog_posts', 'og_image')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->dropColumn('og_image');
            });
        }

        if (Schema::hasTable('ai_content_configs')) {
            Schema::table('ai_content_configs', function (Blueprint $table) {
                $cols = [];
                foreach (['image_model', 'auto_generate_image', 'image_style'] as $col) {
                    if (Schema::hasColumn('ai_content_configs', $col)) {
                        $cols[] = $col;
                    }
                }
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }
    }
};

