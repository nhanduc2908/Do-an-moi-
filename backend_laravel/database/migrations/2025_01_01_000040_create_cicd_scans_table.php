<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cicd_scans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pipeline_id');
            $table->string('repository');
            $table->string('branch');
            $table->string('commit_hash');
            $table->string('scan_type');
            $table->string('tool_name');
            $table->integer('issues_found')->default(0);
            $table->integer('critical_count')->default(0);
            $table->integer('high_count')->default(0);
            $table->boolean('passed')->default(true);
            $table->float('scan_duration')->nullable();
            $table->timestamp('scanned_at');
            $table->timestamps();
            
            $table->index(['pipeline_id', 'scanned_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cicd_scans');
    }
};