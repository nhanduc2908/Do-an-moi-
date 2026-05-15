<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('web_scan_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('target_url');
            $table->integer('scan_depth')->default(3);
            $table->integer('pages_scanned')->default(0);
            $table->integer('vulnerabilities_found')->default(0);
            $table->integer('scan_duration')->nullable();
            $table->string('status')->default('pending');
            $table->text('report_path')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('web_scan_results');
    }
};