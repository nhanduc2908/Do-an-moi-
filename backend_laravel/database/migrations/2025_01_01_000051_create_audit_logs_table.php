<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->json('data')->nullable();
            $table->uuid('user_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('timestamp');
            $table->timestamps();
            
            $table->index(['user_id', 'timestamp']);
            $table->index('event');
            $table->index('ip_address');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};