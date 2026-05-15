<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category');
            $table->integer('duration_minutes');
            $table->string('difficulty_level');
            $table->string('content_url')->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->uuid('created_by')->nullable();
            $table->integer('expiry_days')->default(365);
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['category', 'is_mandatory']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainings');
    }
};