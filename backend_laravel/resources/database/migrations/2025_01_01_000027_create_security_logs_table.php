<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('security_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('event_type');
            $table->string('severity')->default('info');
            $table->string('source');
            $table->uuid('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('message');
            $table->json('details')->nullable();
            $table->timestamp('logged_at');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['event_type', 'severity', 'logged_at']);
            $table->index('ip_address');
        });
    }

    public function down()
    {
        Schema::dropIfExists('security_logs');
    }
};