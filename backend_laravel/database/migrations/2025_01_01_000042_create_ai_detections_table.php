<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ai_detections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('detection_type');
            $table->json('input_data');
            $table->float('confidence_score');
            $table->string('prediction');
            $table->string('model_version');
            $table->float('processing_time_ms');
            $table->timestamp('detected_at');
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
            
            $table->index(['detection_type', 'detected_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_detections');
    }
};