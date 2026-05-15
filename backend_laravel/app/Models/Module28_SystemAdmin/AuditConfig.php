<?php

namespace App\Models\Module28_SystemAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditConfig extends Model
{
    use HasFactory;

    protected $table = 'audit_configs';

    protected $fillable = [
        'event_types', 'retention_days', 'log_level',
        'alert_thresholds', 'is_enabled', 'updated_by'
    ];

    protected $casts = [
        'event_types' => 'array',
        'alert_thresholds' => 'array',
        'is_enabled' => 'boolean',
    ];
}