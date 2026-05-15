<?php

namespace App\Models\Module11_CloudSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudScanResult extends Model
{
    use HasFactory;

    protected $table = 'cloud_scan_results';

    protected $fillable = [
        'provider', 'scan_type', 'resources_scanned', 'issues_found',
        'critical_count', 'high_count', 'medium_count', 'scan_duration', 'scanned_at'
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function issues()
    {
        return $this->hasMany(CloudConfigIssue::class);
    }
}