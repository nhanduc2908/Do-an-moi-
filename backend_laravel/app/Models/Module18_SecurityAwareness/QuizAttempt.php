<?php

namespace App\Models\Module18_SecurityAwareness;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $table = 'quiz_attempts';

    protected $fillable = [
        'quiz_id', 'user_id', 'score', 'max_score',
        'answers', 'started_at', 'completed_at', 'is_passed'
    ];

    protected $casts = [
        'answers' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_passed' => 'boolean',
    ];
}