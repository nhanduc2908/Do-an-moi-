<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('endpoints', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('hostname');
            $table->string('ip_address', 45);
            $table->string('mac_address')->nullable();
            $table->string('os_type');
            $table->string('os_version');
            $table->string('status')->default('active');
            $table->timestamp('last_seen_at');
            $table->uuid('user_id')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['ip_address', 'last_seen_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('endpoints');
    }
};