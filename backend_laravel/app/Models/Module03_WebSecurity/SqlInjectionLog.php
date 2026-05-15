<?php

namespace App\Models\Module03_WebSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SqlInjectionLog extends Model
{
    use HasFactory;

    protected $table = 'sql_injection_logs';

    protected $fillable = [
        'web_scan_result_id', 'url', 'parameter', 'payload', 
        'risk_level', 'details', 'is_blocked'
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
    ];
}