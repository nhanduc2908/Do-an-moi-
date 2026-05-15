<?php

namespace App\Models\Module26_AIEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AICriteriaSuggestion extends Model
{
    use HasFactory;

    protected $table = 'ai_criteria_suggestions';

    protected $fillable = [
        'domain', 'requirements', 'suggested_criteria',
        'confidence_score', 'is_applied', 'applied_at', 'generated_by'
    ];

    protected $casts = [
        'suggested_criteria' => 'array',
        'is_applied' => 'boolean',
        'applied_at' => 'datetime',
    ];
}