<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('api_key_id')->nullable();
            $table->string('endpoint');
            $table->string('method');
            $table->text('request_body')->nullable();
            $table->text('response_body')->nullable();
            $table->integer('status_code');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->integer('duration_ms')->nullable();
            $table->timestamp('created_at');
            
            $table->foreign('api_key_id')->references('id')->on('api_keys')->onDelete('set null');
            $table->index(['endpoint', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_logs');
    }
};