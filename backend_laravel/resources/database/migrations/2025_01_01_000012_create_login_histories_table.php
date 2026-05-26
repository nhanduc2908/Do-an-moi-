<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->timestamp('login_at');
            $table->timestamp('logout_at')->nullable();
            $table->string('status');
            $table->string('failure_reason')->nullable();
            $table->string('location')->nullable();
            $table->string('device_type')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['user_id', 'login_at']);
            $table->index('ip_address');
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_histories');
    }
};