<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('blog_automations')) {
            return;
        }

        Schema::create('blog_automations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->unsignedBigInteger('content_config_id')->index();
            $table->string('frequency', 50)->default('daily'); // daily, weekly, monthly
            $table->time('execution_time')->default('08:00:00');
            $table->string('timezone', 50)->default('Asia/Ho_Chi_Minh');
            $table->smallInteger('day_of_week')->nullable();
            $table->smallInteger('day_of_month')->nullable();
            $table->integer('articles_per_run')->default(1);
            $table->string('publishing_mode', 50)->default('manual_approval');
            $table->integer('interval_minutes')->default(120);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        try {
            Schema::table('blog_automations', function (Blueprint $table) {
                $table->foreign('content_config_id')->references('id')->on('ai_content_configs')->cascadeOnDelete();
            });
        } catch (\Throwable $e) {
            // Safe fallback on MyISAM
        }
    }

    public function down()
    {
        Schema::dropIfExists('blog_automations');
    }
};
