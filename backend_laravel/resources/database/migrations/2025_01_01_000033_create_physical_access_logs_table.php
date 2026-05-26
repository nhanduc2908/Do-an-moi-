<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('physical_access_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('door_id');
            $table->string('access_type');
            $table->string('access_method');
            $table->boolean('access_granted');
            $table->string('reason')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('accessed_at');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['door_id', 'accessed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('physical_access_logs');
    }
};