<?php

namespace App\Models\Module26_AIEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIDetection extends Model
{
    use HasFactory;

    protected $table = 'ai_detections';

    protected $fillable = [
        'detection_type', 'input_data', 'confidence_score',
        'prediction', 'model_version', 'processing_time_ms',
        'detected_at', 'is_correct'
    ];

    protected $casts = [
        'input_data' => 'array',
        'detected_at' => 'datetime',
        'is_correct' => 'boolean',
    ];
}