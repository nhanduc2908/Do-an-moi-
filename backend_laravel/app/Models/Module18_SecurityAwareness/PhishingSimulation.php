<?php

namespace App\Models\Module18_SecurityAwareness;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhishingSimulation extends Model
{
    use HasFactory;

    protected $table = 'phishing_simulations';

    protected $fillable = [
        'name', 'template', 'target_emails', 'clicked_count',
        'reported_count', 'submitted_data_count', 'started_at', 'ended_at'
    ];

    protected $casts = [
        'target_emails' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
}