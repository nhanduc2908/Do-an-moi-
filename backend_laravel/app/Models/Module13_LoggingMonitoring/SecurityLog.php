<?php

namespace App\Models\Module13_LoggingMonitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model
{
    use HasFactory;

    protected $table = 'security_logs';

    protected $fillable = [
        'event_type', 'severity', 'source', 'user_id', 'ip_address',
        'user_agent', 'message', 'details', 'logged_at'
    ];

    protected $casts = [
        'details' => 'array',
        'logged_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}