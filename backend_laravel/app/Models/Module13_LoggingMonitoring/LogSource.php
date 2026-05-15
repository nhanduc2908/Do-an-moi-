<?php

namespace App\Models\Module13_LoggingMonitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSource extends Model
{
    use HasFactory;

    protected $table = 'log_sources';

    protected $fillable = [
        'source_name', 'source_type', 'hostname', 'ip_address',
        'port', 'protocol', 'is_active', 'last_log_received_at',
        'logs_per_day', 'retention_days'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_log_received_at' => 'datetime',
    ];
}