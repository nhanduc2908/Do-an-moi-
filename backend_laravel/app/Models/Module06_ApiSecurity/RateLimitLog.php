<?php

namespace App\Models\Module06_ApiSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateLimitLog extends Model
{
    use HasFactory;

    protected $table = 'rate_limit_logs';

    protected $fillable = [
        'api_key_id', 'ip_address', 'endpoint', 'requests_count',
        'window_start', 'window_end', 'is_blocked'
    ];

    protected $casts = [
        'window_start' => 'datetime',
        'window_end' => 'datetime',
        'is_blocked' => 'boolean',
    ];
}