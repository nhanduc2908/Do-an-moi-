<?php

namespace App\Models\Module23_BackupRecovery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisasterRecoveryPlan extends Model
{
    use HasFactory;

    protected $table = 'disaster_recovery_plans';

    protected $fillable = [
        'plan_name', 'version', 'rtos', 'rpos', 'critical_systems',
        'recovery_procedures', 'test_frequency', 'last_tested_at',
        'next_test_date', 'responsible_team', 'status'
    ];

    protected $casts = [
        'critical_systems' => 'array',
        'recovery_procedures' => 'array',
        'last_tested_at' => 'datetime',
        'next_test_date' => 'datetime',
    ];
}