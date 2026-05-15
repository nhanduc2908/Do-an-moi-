<?php

namespace App\Models\Module07_SourceCode;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeScanResult extends Model
{
    use HasFactory;

    protected $table = 'code_scan_results';

    protected $fillable = [
        'repository', 'branch', 'commit_hash', 'scan_tool', 'total_issues',
        'critical_count', 'high_count', 'medium_count', 'low_count',
        'scan_duration', 'status', 'report_path', 'scanned_at'
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function dependencies()
    {
        return $this->hasMany(DependencyVulnerability::class);
    }

    public function secrets()
    {
        return $this->hasMany(SecretDetected::class);
    }
}