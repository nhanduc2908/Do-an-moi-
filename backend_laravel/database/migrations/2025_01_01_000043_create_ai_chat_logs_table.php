<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ai_chat_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('session_id');
            $table->text('message');
            $table->text('response');
            $table->string('intent')->nullable();
            $table->json('entities')->nullable();
            $table->float('confidence')->default(1);
            $table->timestamp('created_at');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'session_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_chat_logs');
    }
};