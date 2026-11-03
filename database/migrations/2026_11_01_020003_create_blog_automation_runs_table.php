<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('blog_automation_runs')) {
            return;
        }

        Schema::create('blog_automation_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('automation_id')->nullable()->index();
            $table->unsignedBigInteger('content_config_id')->nullable()->index();
            $table->unsignedBigInteger('ai_provider_id')->nullable()->index();
            $table->string('provider_name', 100)->nullable();
            $table->string('model_used', 100)->nullable();
            $table->string('topic_processed', 255)->nullable();
            $table->integer('articles_requested')->default(1);
            $table->integer('articles_created')->default(0);
            $table->integer('articles_failed')->default(0);
            $table->integer('prompt_tokens')->default(0);
            $table->integer('completion_tokens')->default(0);
            $table->integer('total_tokens')->default(0);
            $table->decimal('estimated_cost', 10, 5)->default(0.00000);
            $table->string('status', 50)->default('running');
            $table->text('error_details')->nullable();
            $table->text('created_post_ids')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        try {
            Schema::table('blog_automation_runs', function (Blueprint $table) {
                $table->foreign('automation_id')->references('id')->on('blog_automations')->nullOnDelete();
                $table->foreign('content_config_id')->references('id')->on('ai_content_configs')->nullOnDelete();
                $table->foreign('ai_provider_id')->references('id')->on('ai_providers')->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // Safe fallback on MyISAM
        }
    }

    public function down()
    {
        Schema::dropIfExists('blog_automation_runs');
    }
};
