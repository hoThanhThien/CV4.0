<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_content_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->foreignId('ai_provider_id')->nullable()->constrained('ai_providers')->nullOnDelete();
            $table->string('model_override', 100)->nullable();
            $table->string('topic_mode', 50)->default('admin_topic'); // admin_topic, ai_select, ai_research
            $table->string('main_topic', 255)->nullable();
            $table->text('keywords')->nullable(); // JSON or comma-separated
            $table->string('language', 20)->default('vi'); // vi, en, ...
            $table->string('writing_tone', 50)->default('professional'); // professional, conversational, enthusiastic, academic, witty
            $table->string('target_audience', 150)->default('Software Engineers, Tech Leads, Recruiters');
            $table->string('article_length', 50)->default('medium'); // short, medium, long
            $table->string('category', 100)->default('Technology');
            $table->text('tags')->nullable(); // JSON array
            $table->text('custom_instructions')->nullable();
            $table->boolean('include_faq')->default(true);
            $table->boolean('include_sources')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_content_configs');
    }
};
