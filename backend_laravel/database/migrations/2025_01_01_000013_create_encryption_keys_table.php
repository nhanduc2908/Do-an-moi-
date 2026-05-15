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
            $table->string('type'); // RSA, AES, ECC
            $table->integer('size'); // 2048, 4096, 256, 384
            $table->string('purpose'); // encryption, authentication, signing
            $table->string('status')->default('active'); // active, revoked, expired
            $table->text('public_key')->nullable();
            $table->text('private_key')->nullable();
            $table->text('encrypted_key')->nullable();
            $table->string('fingerprint')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->string('revocation_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->json('tags')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'type', 'purpose']);
            $table->index('expires_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('encryption_keys');
    }
};