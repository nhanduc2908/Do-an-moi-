<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('backup_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('job_name');
            $table->string('backup_type');
            $table->text('source_path');
            $table->text('destination_path');
            $table->string('schedule')->nullable();
            $table->integer('retention_days')->default(30);
            $table->boolean('compression')->default(true);
            $table->boolean('encryption')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->string('status')->default('active');
            $table->uuid('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('backup_jobs');
    }
};