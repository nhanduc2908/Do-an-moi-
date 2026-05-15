<?php

namespace App\Services\Module13_LoggingMonitoring;

use App\Models\Module13_LoggingMonitoring\SecurityLog;
use Illuminate\Support\Facades\Log;

class SiemService
{
    protected $logSources = [];
    protected $correlationRules = [];

    public function ingestLog($source, $logData)
    {
        $log = SecurityLog::create([
            'event_type' => $logData['type'] ?? 'unknown',
            'severity' => $logData['severity'] ?? 'info',
            'source' => $source,
            'user_id' => $logData['user_id'] ?? null,
            'ip_address' => $logData['ip_address'] ?? request()->ip(),
            'user_agent' => $logData['user_agent'] ?? request()->userAgent(),
            'message' => $logData['message'] ?? '',
            'details' => $logData['details'] ?? [],
            'logged_at' => $logData['timestamp'] ?? now()
        ]);
        
        $this->correlateEvents($log);
        
        return $log;
    }

    public function correlateEvents($log)
    {
        // Check for failed login attempts
        if ($log->event_type === 'failed_login') {
            $recentFailures = SecurityLog::where('event_type', 'failed_login')
                ->where('ip_address', $log->ip_address)
                ->where('logged_at', '>', now()->subMinutes(5))
                ->count();
            
            if ($recentFailures >= 5) {
                $this->generateAlert([
                    'type' => 'brute_force_attempt',
                    'severity' => 'high',
                    'details' => "Multiple failed logins from IP {$log->ip_address}",
                    'source' => $log->ip_address
                ]);
            }
        }
        
        // Check for privilege escalation
        if ($log->event_type === 'role_assigned') {
            $recentRoleChanges = SecurityLog::where('user_id', $log->user_id)
                ->where('event_type', 'role_assigned')
                ->where('logged_at', '>', now()->subHours(24))
                ->count();
            
            if ($recentRoleChanges > 3) {
                $this->generateAlert([
                    'type' => 'excessive_role_changes',
                    'severity' => 'medium',
                    'details' => "User {$log->user_id} has had multiple role assignments",
                    'source' => $log->user_id
                ]);
            }
        }
    }

    protected function generateAlert($alertData)
    {
        Alert::create([
            'alert_name' => $alertData['type'],
            'severity' => $alertData['severity'],
            'status' => 'new',
            'source' => $alertData['source'],
            'message' => $alertData['details'],
            'triggered_at' => now(),
            'details' => $alertData
        ]);
        
        Log::warning('SIEM Alert', $alertData);
    }

    public function searchLogs($criteria, $from, $to)
    {
        $query = SecurityLog::query();
        
        if (isset($criteria['event_type'])) {
            $query->where('event_type', $criteria['event_type']);
        }
        
        if (isset($criteria['severity'])) {
            $query->where('severity', $criteria['severity']);
        }
        
        if (isset($criteria['user_id'])) {
            $query->where('user_id', $criteria['user_id']);
        }
        
        if (isset($criteria['ip_address'])) {
            $query->where('ip_address', $criteria['ip_address']);
        }
        
        $query->whereBetween('logged_at', [$from, $to]);
        
        return $query->orderBy('logged_at', 'desc')->paginate(50);
    }

    public function getDashboardStats($hours = 24)
    {
        $since = now()->subHours($hours);
        
        return [
            'total_events' => SecurityLog::where('logged_at', '>=', $since)->count(),
            'by_severity' => SecurityLog::where('logged_at', '>=', $since)
                ->select('severity', \DB::raw('count(*) as count'))
                ->groupBy('severity')
                ->get(),
            'by_event_type' => SecurityLog::where('logged_at', '>=', $since)
                ->select('event_type', \DB::raw('count(*) as count'))
                ->groupBy('event_type')
                ->limit(10)
                ->get(),
            'top_ips' => SecurityLog::where('logged_at', '>=', $since)
                ->select('ip_address', \DB::raw('count(*) as count'))
                ->groupBy('ip_address')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
        ];
    }
}