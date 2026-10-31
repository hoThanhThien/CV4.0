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
        Schema::create('blog_automation_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('automation_id')->nullable()->constrained('blog_automations')->nullOnDelete();
            $table->foreignId('content_config_id')->nullable()->constrained('ai_content_configs')->nullOnDelete();
            $table->foreignId('ai_provider_id')->nullable()->constrained('ai_providers')->nullOnDelete();
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
            $table->string('status', 50)->default('running'); // running, completed, partially_completed, failed, skipped
            $table->text('error_details')->nullable();
            $table->text('created_post_ids')->nullable(); // JSON array of created BlogPost IDs
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('blog_automation_runs');
    }
};
