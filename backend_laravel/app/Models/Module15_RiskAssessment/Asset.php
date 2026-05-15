<?php

namespace App\Models\Module15_RiskAssessment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';

    protected $fillable = [
        'asset_name', 'asset_type', 'classification', 'owner',
        'location', 'value', 'criticality', 'confidentiality',
        'integrity', 'availability', 'description'
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];

    public function riskAssessments()
    {
        return $this->hasMany(RiskAssessment::class);
    }
}