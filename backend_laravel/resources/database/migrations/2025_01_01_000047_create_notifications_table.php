<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->uuid('user_id');
            $table->uuid('sender_id')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->string('priority')->default('normal');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['user_id', 'read_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};