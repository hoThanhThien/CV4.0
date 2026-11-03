<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('ai_providers')) {
            return;
        }

        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('provider_type', 50); // openai, gemini, deepseek, custom
            $table->text('api_key')->nullable(); // Encrypted via Crypt
            $table->string('model', 100);
            $table->string('base_url', 255)->nullable();
            $table->decimal('temperature', 3, 2)->default(0.70);
            $table->integer('max_tokens')->default(8192);
            $table->integer('timeout')->default(90);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamp('last_tested_at')->nullable();
            $table->string('connection_status', 50)->default('untested'); // connected, failed, untested
            $table->text('last_error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_providers');
    }
};
