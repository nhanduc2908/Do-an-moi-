<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('risk_name');
            $table->text('risk_description');
            $table->string('risk_level');
            $table->integer('inherent_likelihood');
            $table->integer('inherent_impact');
            $table->integer('inherent_risk_score');
            $table->integer('residual_likelihood')->nullable();
            $table->integer('residual_impact')->nullable();
            $table->integer('residual_risk_score')->nullable();
            $table->timestamp('assessment_date');
            $table->uuid('assessed_by')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('review_date')->nullable();
            $table->timestamps();
            
            $table->foreign('assessed_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['risk_level', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('risk_assessments');
    }
};