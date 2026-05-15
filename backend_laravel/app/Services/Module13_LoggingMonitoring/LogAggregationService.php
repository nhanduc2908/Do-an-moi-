<?php

namespace App\Services\Module13_LoggingMonitoring;

use App\Models\Module13_LoggingMonitoring\LogSource;
use Illuminate\Support\Facades\Http;

class LogAggregationService
{
    protected $logSources = [];

    public function addLogSource($source)
    {
        $logSource = LogSource::create($source);
        $this->logSources[$logSource->id] = $logSource;
        return $logSource;
    }

    public function aggregateLogs()
    {
        $sources = LogSource::where('is_active', true)->get();
        $allLogs = [];
        
        foreach ($sources as $source) {
            $logs = $this->fetchLogs($source);
            $allLogs = array_merge($allLogs, $logs);
        }
        
        return $this->normalizeLogs($allLogs);
    }

    protected function fetchLogs($source)
    {
        switch ($source->source_type) {
            case 'file':
                return $this->fetchFromFile($source);
            case 'syslog':
                return $this->fetchFromSyslog($source);
            case 'api':
                return $this->fetchFromApi($source);
            case 'database':
                return $this->fetchFromDatabase($source);
            default:
                return [];
        }
    }

    protected function fetchFromFile($source)
    {
        $files = glob($source->path);
        $logs = [];
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $lines = explode("\n", $content);
            
            foreach ($lines as $line) {
                if (!empty($line)) {
                    $logs[] = [
                        'source' => $source->source_name,
                        'raw' => $line,
                        'timestamp' => filemtime($file)
                    ];
                }
            }
        }
        
        return $logs;
    }

    protected function fetchFromSyslog($source)
    {
        // Syslog collection via UDP/TCP
        return [];
    }

    protected function fetchFromApi($source)
    {
        $response = Http::withHeaders($source->headers ?? [])
            ->get($source->endpoint);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        return [];
    }

    protected function fetchFromDatabase($source)
    {
        // Query database logs
        return [];
    }

    protected function normalizeLogs($logs)
    {
        $normalized = [];
        
        foreach ($logs as $log) {
            $normalized[] = [
                'source' => $log['source'] ?? 'unknown',
                'timestamp' => $log['timestamp'] ?? $log['time'] ?? now(),
                'level' => $log['level'] ?? $log['severity'] ?? 'info',
                'message' => $log['message'] ?? $log['raw'] ?? json_encode($log),
                'metadata' => $log
            ];
        }
        
        return $normalized;
    }
}