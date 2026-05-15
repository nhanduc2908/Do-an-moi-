<?php

namespace App\Models\Module25_PenetrationTesting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialEngineeringLog extends Model
{
    use HasFactory;

    protected $table = 'social_engineering_logs';

    protected $fillable = [
        'campaign_name', 'target_emails', 'technique',
        'success_rate', 'clicked_count', 'reported_count',
        'started_at', 'ended_at', 'lessons_learned'
    ];

    protected $casts = [
        'target_emails' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
}