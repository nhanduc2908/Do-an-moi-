<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('incident_code')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('severity');
            $table->string('status')->default('open');
            $table->string('category');
            $table->timestamp('detected_at');
            $table->timestamp('reported_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->uuid('reported_by')->nullable();
            $table->uuid('assigned_to')->nullable();
            $table->text('resolution_summary')->nullable();
            $table->timestamps();
            
            $table->foreign('reported_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->index(['severity', 'status', 'detected_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidents');
    }
};