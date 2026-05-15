<?php

namespace App\Models\Module03_WebSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebScanResult extends Model
{
    use HasFactory;

    protected $table = 'web_scan_results';

    protected $fillable = [
        'target_url', 'scan_depth', 'pages_scanned', 'vulnerabilities_found',
        'scan_duration', 'status', 'report_path', 'started_at', 'completed_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function sqlInjections()
    {
        return $this->hasMany(SqlInjectionLog::class);
    }

    public function xssLogs()
    {
        return $this->hasMany(XssLog::class);
    }
}