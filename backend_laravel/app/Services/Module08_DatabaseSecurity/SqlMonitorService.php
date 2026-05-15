<?php

namespace App\Services\Module08_DatabaseSecurity;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SqlMonitorService
{
    protected $slowQueryThreshold = 1000; // milliseconds
    protected $suspiciousPatterns = [
        '/\bdrop\b/i',
        '/\btruncate\b/i',
        '/\balter\b.*\b(drop|add|modify)\b/i',
        '/\bdelete\b.*\bfrom\b/i',
        '/\bupdate\b.*\bwhere\b.*\b(1=1|true|false|null)\b/i'
    ];

    public function __construct()
    {
        DB::listen(function($query) {
            $this->analyzeQuery($query);
        });
    }

    public function analyzeQuery($query)
    {
        $sql = $query->sql;
        $bindings = $query->bindings;
        $time = $query->time;
        
        // Check for slow queries
        if ($time > $this->slowQueryThreshold) {
            $this->reportSlowQuery($sql, $bindings, $time);
        }
        
        // Check for suspicious queries
        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $sql)) {
                $this->reportSuspiciousQuery($sql, $bindings, $pattern);
            }
        }
    }

    protected function reportSlowQuery($sql, $bindings, $time)
    {
        Log::warning('Slow query detected', [
            'sql' => $sql,
            'bindings' => $bindings,
            'time_ms' => $time,
            'threshold_ms' => $this->slowQueryThreshold
        ]);
    }

    protected function reportSuspiciousQuery($sql, $bindings, $pattern)
    {
        Log::alert('Suspicious SQL query detected', [
            'sql' => $sql,
            'bindings' => $bindings,
            'pattern' => $pattern,
            'user' => auth()->id(),
            'ip' => request()->ip()
        ]);
    }

    public function setSlowQueryThreshold($milliseconds)
    {
        $this->slowQueryThreshold = $milliseconds;
    }
}