<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('encrypted_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->bigInteger('file_size');
            
            // Encryption fields
            $table->string('encryption_algorithm')->default('aes-256-gcm');
            $table->text('encrypted_key')->nullable();      // DEK encrypted with KEK
            $table->string('iv')->nullable();                // Initialization vector
            $table->string('tag')->nullable();               // Authentication tag (GCM mode)
            $table->string('file_hash')->nullable();         // SHA-256 hash of original file
            
            // Access control fields (hierarchical)
            $table->integer('required_level')->default(0);    // Minimum required level (1-100)
            $table->json('allowed_roles')->nullable();        // Specific roles allowed
            $table->json('allowed_users')->nullable();        // Specific users allowed
            
            // Classification
            $table->enum('classification', [
                'public', 'internal', 'confidential', 'restricted', 'top_secret'
            ])->default('internal');
            
            // Metadata
            $table->uuid('uploaded_by');
            $table->timestamp('uploaded_at');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->integer('access_count')->default(0);
            $table->boolean('is_deleted')->default(false);
            $table->softDeletes();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('restrict');
            
            // Indexes for performance
            $table->index(['classification', 'required_level']);
            $table->index('uploaded_at');
            $table->index('expires_at');
            $table->index('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encrypted_documents');
    }
};