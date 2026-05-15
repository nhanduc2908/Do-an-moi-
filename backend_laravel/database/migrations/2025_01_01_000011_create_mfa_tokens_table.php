<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mfa_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('token');
            $table->string('type')->default('email');
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'token']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mfa_tokens');
    }
};