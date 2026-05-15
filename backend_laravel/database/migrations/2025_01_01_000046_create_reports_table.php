<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('report_name');
            $table->string('report_type');
            $table->json('filters')->nullable();
            $table->string('format');
            $table->text('file_path')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->uuid('generated_by');
            $table->timestamp('generated_at');
            $table->timestamp('expires_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();
            
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['report_type', 'generated_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};