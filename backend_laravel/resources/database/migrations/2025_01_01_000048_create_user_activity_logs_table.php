<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('activity_type');
            $table->text('description')->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'activity_type', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_activity_logs');
    }
};