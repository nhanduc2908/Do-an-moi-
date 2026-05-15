<?php

namespace App\Models\Module11_CloudSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudConfigIssue extends Model
{
    use HasFactory;

    protected $table = 'cloud_config_issues';

    protected $fillable = [
        'cloud_scan_result_id', 'resource_id', 'issue_type', 'severity',
        'description', 'remediation', 'is_fixed', 'fixed_at'
    ];

    protected $casts = [
        'is_fixed' => 'boolean',
        'fixed_at' => 'datetime',
    ];
}