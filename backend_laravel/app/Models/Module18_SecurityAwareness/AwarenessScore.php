<?php

namespace App\Models\Module18_SecurityAwareness;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwarenessScore extends Model
{
    use HasFactory;

    protected $table = 'awareness_scores';

    protected $fillable = [
        'user_id', 'overall_score', 'trainings_completed',
        'quizzes_passed', 'phishing_resilience_score', 'calculated_at'
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}