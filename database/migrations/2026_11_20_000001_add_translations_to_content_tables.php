<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add nullable JSON `translations` column to core CMS content tables for bilingual EN/VI support.
     */
    public function up(): void
    {
        foreach (['projects', 'certificates', 'blog_posts'] as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'translations')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->json('translations')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['projects', 'certificates', 'blog_posts'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'translations')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('translations');
                });
            }
        }
    }
};
