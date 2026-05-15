<?php

namespace App\Models\Module15_RiskAssessment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskTreatment extends Model
{
    use HasFactory;

    protected $table = 'risk_treatments';

    protected $fillable = [
        'risk_assessment_id', 'treatment_type', 'control_description',
        'implementation_status', 'responsible_party', 'start_date',
        'completion_date', 'cost_estimate', 'effectiveness'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'completion_date' => 'datetime',
        'cost_estimate' => 'decimal:2',
    ];

    public function riskAssessment()
    {
        return $this->belongsTo(RiskAssessment::class);
    }
}