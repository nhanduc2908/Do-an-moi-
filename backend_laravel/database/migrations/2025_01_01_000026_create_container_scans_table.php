<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('container_scans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('image_name');
            $table->string('image_tag');
            $table->string('image_digest')->nullable();
            $table->bigInteger('size')->default(0);
            $table->integer('vulnerabilities_count')->default(0);
            $table->integer('critical_count')->default(0);
            $table->integer('high_count')->default(0);
            $table->integer('medium_count')->default(0);
            $table->integer('low_count')->default(0);
            $table->string('scan_tool');
            $table->timestamp('scanned_at');
            $table->boolean('is_vulnerable')->default(false);
            $table->text('report_path')->nullable();
            $table->timestamps();
            
            $table->index(['image_name', 'scanned_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('container_scans');
    }
};