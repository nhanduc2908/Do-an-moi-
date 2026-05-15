<?php

namespace App\Services\Module28_SystemAdmin;

use App\Models\Module28_SystemAdmin\AuditConfig;

class AuditLogService
{
    public function log($event, $data = [])
    {
        $auditEntry = [
            'event' => $event,
            'data' => $data,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ];
        
        // Store in database
        \DB::table('audit_logs')->insert($auditEntry);
        
        // Forward to SIEM if configured
        $this->forwardToSiem($auditEntry);
        
        return $auditEntry;
    }

    public function search($criteria)
    {
        $query = \DB::table('audit_logs');
        
        if (isset($criteria['event'])) {
            $query->where('event', $criteria['event']);
        }
        
        if (isset($criteria['user_id'])) {
            $query->where('user_id', $criteria['user_id']);
        }
        
        if (isset($criteria['from'])) {
            $query->where('timestamp', '>=', $criteria['from']);
        }
        
        if (isset($criteria['to'])) {
            $query->where('timestamp', '<=', $criteria['to']);
        }
        
        if (isset($criteria['ip_address'])) {
            $query->where('ip_address', $criteria['ip_address']);
        }
        
        return $query->orderBy('timestamp', 'desc')->paginate(50);
    }

    protected function forwardToSiem($auditEntry)
    {
        $config = AuditConfig::first();
        
        if ($config && $config->is_enabled) {
            // Forward to SIEM via syslog or API
            syslog(LOG_INFO, json_encode($auditEntry));
        }
    }

    public function getAuditStatistics($days = 30)
    {
        $logs = \DB::table('audit_logs')
            ->where('timestamp', '>=', now()->subDays($days))
            ->get();
        
        return [
            'total_events' => $logs->count(),
            'by_event_type' => $logs->groupBy('event')->map->count(),
            'by_user' => $logs->groupBy('user_id')->map->count(),
            'by_ip' => $logs->groupBy('ip_address')->map->count(),
            'daily_average' => $logs->count() / $days,
            'peak_hours' => $logs->groupBy(function($log) {
                return date('H', strtotime($log->timestamp));
            })->map->count()->sortDesc()->take(5)
        ];
    }

    public function cleanupOldLogs($retentionDays = 90)
    {
        $deleted = \DB::table('audit_logs')
            ->where('timestamp', '<', now()->subDays($retentionDays))
            ->delete();
        
        return ['deleted' => $deleted];
    }
}