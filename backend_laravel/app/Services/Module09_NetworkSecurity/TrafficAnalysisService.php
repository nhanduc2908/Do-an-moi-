<?php

namespace App\Services\Module09_NetworkSecurity;

use App\Models\Module09_NetworkSecurity\TrafficLog;

class TrafficAnalysisService
{
    public function analyzeTraffic($timeWindow = 3600)
    {
        $traffic = TrafficLog::where('start_time', '>=', now()->subSeconds($timeWindow))
            ->get();
        
        $analysis = [
            'total_bytes_sent' => $traffic->sum('bytes_sent'),
            'total_bytes_received' => $traffic->sum('bytes_received'),
            'top_sources' => $this->getTopSources($traffic),
            'top_destinations' => $this->getTopDestinations($traffic),
            'protocol_distribution' => $this->getProtocolDistribution($traffic),
            'anomalies' => $this->detectAnomalies($traffic)
        ];
        
        return $analysis;
    }

    protected function getTopSources($traffic)
    {
        return $traffic->groupBy('source_ip')
            ->map(function($group) {
                return $group->sum('bytes_sent');
            })
            ->sortDesc()
            ->take(10);
    }

    protected function getTopDestinations($traffic)
    {
        return $traffic->groupBy('destination_ip')
            ->map(function($group) {
                return $group->sum('bytes_received');
            })
            ->sortDesc()
            ->take(10);
    }

    protected function getProtocolDistribution($traffic)
    {
        return $traffic->groupBy('protocol')
            ->map(function($group) {
                return $group->count();
            });
    }

    protected function detectAnomalies($traffic)
    {
        $anomalies = [];
        $threshold = 1000000; // 1MB threshold
        
        $highVolume = $traffic->filter(function($log) use ($threshold) {
            return ($log->bytes_sent + $log->bytes_received) > $threshold;
        });
        
        if ($highVolume->count() > 0) {
            $anomalies[] = [
                'type' => 'high_volume_traffic',
                'count' => $highVolume->count(),
                'details' => $highVolume->pluck('source_ip')->toArray()
            ];
        }
        
        return $anomalies;
    }
}