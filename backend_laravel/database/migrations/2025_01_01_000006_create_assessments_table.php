<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('assessment_type');
            $table->uuid('target_system_id')->nullable();
            $table->string('status')->default('draft');
            $table->integer('progress')->default(0);
            $table->decimal('score', 5, 2)->nullable();
            $table->string('risk_level')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->uuid('assigned_to')->nullable();
            $table->uuid('created_by');
            $table->json('scope')->nullable();
            $table->json('findings')->nullable();
            $table->json('recommendations')->nullable();
            $table->timestamps();
            
            $table->foreign('assigned_to')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
        });

        Schema::create('assessment_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_id');
            $table->uuid('criteria_id');
            $table->text('response')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->json('evidence')->nullable();
            $table->text('comments')->nullable();
            $table->string('status')->default('pending');
            $table->uuid('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
            $table->foreign('criteria_id')->references('id')->on('criteria');
            $table->foreign('reviewed_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assessment_details');
        Schema::dropIfExists('assessments');
    }
};