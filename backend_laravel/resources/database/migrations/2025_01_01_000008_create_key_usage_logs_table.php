<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('key_usage_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('encryption_key_id');
            $table->string('action');
            $table->uuid('user_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('details')->nullable();
            $table->timestamp('performed_at');
            $table->timestamps();
            
            $table->foreign('encryption_key_id')->references('id')->on('encryption_keys')->onDelete('cascade');
            $table->index(['encryption_key_id', 'action']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('key_usage_logs');
    }
};