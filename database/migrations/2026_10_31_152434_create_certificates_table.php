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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('certificate'); // certificate, brochure, degree, award, other
            $table->string('issuer')->nullable();
            $table->string('issue_date')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_type')->default('pdf'); // pdf, image
            $table->string('file_size')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('featured')->default(true);
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('certificates');
    }
};
