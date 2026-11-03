<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('ai_content_configs')) {
            return;
        }

        Schema::create('ai_content_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->unsignedBigInteger('ai_provider_id')->nullable()->index();
            $table->string('model_override', 100)->nullable();
            $table->string('topic_mode', 50)->default('admin_topic'); // admin_topic, ai_select, ai_research
            $table->string('main_topic', 255)->nullable();
            $table->text('keywords')->nullable(); // JSON or comma-separated
            $table->string('language', 20)->default('vi');
            $table->string('writing_tone', 50)->default('professional');
            $table->string('target_audience', 150)->default('Software Engineers, Tech Leads, Recruiters');
            $table->string('article_length', 50)->default('medium');
            $table->string('category', 100)->default('Technology');
            $table->text('tags')->nullable(); // JSON array
            $table->text('custom_instructions')->nullable();
            $table->boolean('include_faq')->default(true);
            $table->boolean('include_sources')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        try {
            Schema::table('ai_content_configs', function (Blueprint $table) {
                $table->foreign('ai_provider_id')->references('id')->on('ai_providers')->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // Safe fallback if hosting MySQL engine does not support foreign keys (e.g. MyISAM)
        }
    }

    public function down()
    {
        Schema::dropIfExists('ai_content_configs');
    }
};
