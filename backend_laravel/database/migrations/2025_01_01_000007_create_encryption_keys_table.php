<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('encryption_keys', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key_id')->unique();
            $table->enum('type', ['RSA', 'AES', 'ECC', 'ChaCha20']);
            $table->integer('size')->comment('Key size in bits: 128, 256, 2048, 4096');
            $table->enum('purpose', ['encryption', 'authentication', 'signing', 'backup']);
            $table->enum('status', ['active', 'revoked', 'expired', 'pending'])->default('active');
            $table->text('public_key')->nullable();
            $table->text('private_key')->nullable();
            $table->text('encrypted_key')->nullable()->comment('Store encrypted private key');
            $table->string('fingerprint', 64)->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->string('revocation_reason', 255)->nullable();
            $table->json('metadata')->nullable();
            $table->json('tags')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Indexes for performance
            $table->index(['status', 'type', 'purpose']);
            $table->index('expires_at');
            $table->index('status');
            $table->index('type');
            $table->index('purpose');
            $table->index('fingerprint');
        });
    }

    public function down()
    {
        Schema::dropIfExists('encryption_keys');
    }
};