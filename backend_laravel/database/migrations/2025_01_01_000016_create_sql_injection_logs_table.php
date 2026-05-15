<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sql_injection_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('web_scan_result_id')->nullable();
            $table->text('url');
            $table->string('parameter');
            $table->text('payload');
            $table->string('risk_level')->default('medium');
            $table->json('details')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->timestamps();
            
            $table->foreign('web_scan_result_id')->references('id')->on('web_scan_results')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sql_injection_logs');
    }
};