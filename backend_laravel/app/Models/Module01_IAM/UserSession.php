<?php

namespace App\Models\Module01_IAM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    protected $table = 'user_sessions';

    protected $fillable = [
        'user_id', 'session_id', 'ip_address', 'user_agent', 
        'payload', 'last_activity', 'device_type', 'is_active'
    ];

    protected $casts = [
        'last_activity' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}