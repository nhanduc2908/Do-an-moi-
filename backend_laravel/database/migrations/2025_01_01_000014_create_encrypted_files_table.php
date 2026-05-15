<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('encrypted_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('encryption_key_id')->nullable();
            $table->string('original_name');
            $table->string('encrypted_name');
            $table->text('path');
            $table->bigInteger('size');
            $table->string('algorithm');
            $table->string('iv')->nullable();
            $table->string('tag')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->foreign('encryption_key_id')->references('id')->on('encryption_keys')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('encrypted_files');
    }
};