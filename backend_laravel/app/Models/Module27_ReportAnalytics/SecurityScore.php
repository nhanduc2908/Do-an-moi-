<?php

namespace App\Models\Module27_ReportAnalytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityScore extends Model
{
    use HasFactory;

    protected $table = 'security_scores';

    protected $fillable = [
        'organization_id', 'overall_score', 'category_scores',
        'trend', 'calculated_at', 'valid_until'
    ];

    protected $casts = [
        'category_scores' => 'array',
        'calculated_at' => 'datetime',
        'valid_until' => 'datetime',
    ];
}