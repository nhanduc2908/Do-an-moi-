<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('email_scans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email_id')->nullable();
            $table->string('sender');
            $table->string('recipient');
            $table->string('subject');
            $table->string('scan_status')->default('pending');
            $table->string('threat_level')->nullable();
            $table->string('threat_type')->nullable();
            $table->json('attachments')->nullable();
            $table->json('links')->nullable();
            $table->timestamp('scanned_at');
            $table->boolean('is_malicious')->default(false);
            $table->timestamps();
            
            $table->index(['sender', 'scanned_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_scans');
    }
};