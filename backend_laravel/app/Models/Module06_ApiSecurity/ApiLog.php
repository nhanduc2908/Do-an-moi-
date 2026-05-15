<?php

namespace App\Models\Module06_ApiSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $table = 'api_logs';

    protected $fillable = [
        'api_key_id', 'endpoint', 'method', 'request_body', 'response_body',
        'status_code', 'ip_address', 'user_agent', 'duration_ms', 'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function apiKey()
    {
        return $this->belongsTo(ApiKey::class);
    }
}