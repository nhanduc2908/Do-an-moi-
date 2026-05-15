<?php

namespace App\Models\Module01_IAM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    protected $table = 'login_histories';

    protected $fillable = [
        'user_id', 'ip_address', 'user_agent', 'login_at', 'logout_at',
        'status', 'failure_reason', 'location', 'device_type'
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}