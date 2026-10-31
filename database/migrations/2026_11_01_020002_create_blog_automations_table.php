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
        Schema::create('blog_automations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->foreignId('content_config_id')->constrained('ai_content_configs')->cascadeOnDelete();
            $table->string('frequency', 50)->default('daily'); // daily, weekly, monthly
            $table->time('execution_time')->default('08:00:00'); // Store in HH:MM:SS
            $table->string('timezone', 50)->default('Asia/Ho_Chi_Minh');
            $table->smallInteger('day_of_week')->nullable(); // 0 = Sunday, 1 = Monday, etc.
            $table->smallInteger('day_of_month')->nullable(); // 1 - 31
            $table->integer('articles_per_run')->default(1);
            $table->string('publishing_mode', 50)->default('manual_approval'); // manual_approval, auto_schedule, auto_publish
            $table->integer('interval_minutes')->default(120); // Interval between multiple articles in same run
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamp('last_run_at')->nullable();
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
        Schema::dropIfExists('blog_automations');
    }
};
