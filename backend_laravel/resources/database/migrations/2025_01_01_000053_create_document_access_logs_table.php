<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_access_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('document_id');
            $table->uuid('user_id');
            $table->string('action');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->boolean('access_granted');
            $table->string('denial_reason')->nullable();
            $table->text('justification')->nullable();
            $table->timestamp('accessed_at');
            $table->timestamps();
            
            $table->foreign('document_id')->references('id')->on('encrypted_documents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['document_id', 'accessed_at']);
            $table->index(['user_id', 'access_granted']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_access_logs');
    }
};