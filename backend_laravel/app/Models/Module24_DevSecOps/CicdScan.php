<?php

namespace App\Models\Module24_DevSecOps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicdScan extends Model
{
    use HasFactory;

    protected $table = 'cicd_scans';

    protected $fillable = [
        'pipeline_id', 'repository', 'branch', 'commit_hash',
        'scan_type', 'tool_name', 'issues_found', 'critical_count',
        'high_count', 'passed', 'scan_duration', 'scanned_at'
    ];

    protected $casts = [
        'passed' => 'boolean',
        'scanned_at' => 'datetime',
    ];
}