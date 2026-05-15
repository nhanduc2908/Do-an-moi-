<?php

namespace App\Models\Module26_AIEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIPrediction extends Model
{
    use HasFactory;

    protected $table = 'ai_predictions';

    protected $fillable = [
        'prediction_type', 'model_name', 'features', 'predicted_value',
        'actual_value', 'accuracy', 'predicted_at'
    ];

    protected $casts = [
        'features' => 'array',
        'predicted_at' => 'datetime',
    ];
}