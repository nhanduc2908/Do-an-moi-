<?php

namespace App\Models\Module26_AIEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIAnomaly extends Model
{
    use HasFactory;

    protected $table = 'ai_anomalies';

    protected $fillable = [
        'anomaly_score', 'feature_importance', 'threshold',
        'detection_id', 'data_point', 'anomaly_type'
    ];

    protected $casts = [
        'feature_importance' => 'array',
        'data_point' => 'array',
    ];

    public function detection()
    {
        return $this->belongsTo(AIDetection::class);
    }
}