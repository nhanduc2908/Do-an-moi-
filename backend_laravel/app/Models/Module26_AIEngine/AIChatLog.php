<?php

namespace App\Models\Module26_AIEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIChatLog extends Model
{
    use HasFactory;

    protected $table = 'ai_chat_logs';

    protected $fillable = [
        'user_id', 'session_id', 'message', 'response',
        'intent', 'entities', 'confidence', 'created_at'
    ];

    protected $casts = [
        'entities' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}