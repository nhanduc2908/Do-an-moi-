<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('xss_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('web_scan_result_id')->nullable();
            $table->text('url');
            $table->string('parameter');
            $table->text('payload');
            $table->string('type')->default('reflected');
            $table->string('risk_level')->default('medium');
            $table->json('details')->nullable();
            $table->timestamps();
            
            $table->foreign('web_scan_result_id')->references('id')->on('web_scan_results')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('xss_logs');
    }
};