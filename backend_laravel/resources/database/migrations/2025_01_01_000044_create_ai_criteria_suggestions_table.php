<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ai_criteria_suggestions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('domain');
            $table->text('requirements');
            $table->json('suggested_criteria');
            $table->float('confidence_score');
            $table->boolean('is_applied')->default(false);
            $table->timestamp('applied_at')->nullable();
            $table->string('generated_by')->default('ai');
            $table->timestamps();
            
            $table->index(['domain', 'is_applied']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_criteria_suggestions');
    }
};