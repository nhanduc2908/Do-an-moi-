<?php

namespace App\Models\Module15_RiskAssessment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    use HasFactory;

    protected $table = 'risk_scores';

    protected $fillable = [
        'risk_assessment_id', 'score_type', 'likelihood', 'impact',
        'risk_value', 'risk_level', 'calculated_at'
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
    ];

    public function riskAssessment()
    {
        return $this->belongsTo(RiskAssessment::class);
    }
}