<?php

namespace App\Models\Module15_RiskAssessment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Threat extends Model
{
    use HasFactory;

    protected $table = 'threats';

    protected $fillable = [
        'threat_name', 'threat_category', 'source', 'capability',
        'intent', 'likelihood', 'impact', 'description', 'mitigation'
    ];

    protected $casts = [
        'likelihood' => 'integer',
        'impact' => 'integer',
    ];

    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'asset_threats');
    }
}