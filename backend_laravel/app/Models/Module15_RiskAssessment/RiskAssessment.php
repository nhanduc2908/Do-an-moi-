<?php

namespace App\Models\Module15_RiskAssessment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskAssessment extends Model
{
    use HasFactory;

    protected $table = 'risk_assessments';

    protected $fillable = [
        'asset_id', 'risk_name', 'risk_description', 'risk_level',
        'inherent_likelihood', 'inherent_impact', 'inherent_risk_score',
        'residual_likelihood', 'residual_impact', 'residual_risk_score',
        'assessment_date', 'assessed_by', 'status', 'review_date'
    ];

    protected $casts = [
        'assessment_date' => 'datetime',
        'review_date' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function assessor()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'assessed_by');
    }

    public function treatments()
    {
        return $this->hasMany(RiskTreatment::class);
    }
}