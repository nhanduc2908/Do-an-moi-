<?php

namespace App\Services\Module20_IotSecurity;

use App\Models\Module20_IotSecurity\IotTraffic;

class IotTrafficService
{
    public function logTraffic($deviceId, $trafficData)
    {
        $traffic = IotTraffic::create([
            'device_id' => $deviceId,
            'destination_ip' => $trafficData['dest_ip'],
            'destination_port' => $trafficData['dest_port'],
            'protocol' => $trafficData['protocol'],
            'bytes_sent' => $trafficData['bytes_sent'],
            'bytes_received' => $trafficData['bytes_received'],
            'timestamp' => now(),
            'is_suspicious' => $this->detectSuspiciousTraffic($trafficData)
        ]);
        
        if ($traffic->is_suspicious) {
            $this->alertSuspiciousTraffic($traffic);
        }
        
        return $traffic;
    }

    protected function detectSuspiciousTraffic($trafficData)
    {
        $suspicious = false;
        
        // Check for communication with known malicious IPs
        $maliciousIps = cache()->get('malicious_ips', []);
        if (in_array($trafficData['dest_ip'], $maliciousIps)) {
            $suspicious = true;
        }
        
        // Check for unusual ports
        $unusualPorts = [23, 2323, 5555, 6666, 8888];
        if (in_array($trafficData['dest_port'], $unusualPorts)) {
            $suspicious = true;
        }
        
        // Check for high data volume
        if ($trafficData['bytes_sent'] > 1000000) { // 1MB
            $suspicious = true;
        }
        
        return $suspicious;
    }

    protected function alertSuspiciousTraffic($traffic)
    {
        // Send alert to security team
        \Log::warning('Suspicious IoT traffic detected', [
            'device_id' => $traffic->device_id,
            'destination' => $traffic->destination_ip,
            'port' => $traffic->destination_port,
            'bytes_sent' => $traffic->bytes_sent
        ]);
    }

    public function getTrafficAnalysis($deviceId, $hours = 24)
    {
        $traffic = IotTraffic::where('device_id', $deviceId)
            ->where('timestamp', '>=', now()->subHours($hours))
            ->get();
        
        return [
            'total_traffic_mb' => ($traffic->sum('bytes_sent') + $traffic->sum('bytes_received')) / 1048576,
            'unique_destinations' => $traffic->pluck('destination_ip')->unique()->count(),
            'suspicious_count' => $traffic->where('is_suspicious', true)->count(),
            'protocol_breakdown' => $traffic->groupBy('protocol')->map->count(),
            'top_destinations' => $traffic->groupBy('destination_ip')
                ->map(function($group) {
                    return [
                        'total_bytes' => $group->sum('bytes_sent'),
                        'count' => $group->count()
                    ];
                })
                ->sortByDesc('total_bytes')
                ->take(10)
        ];
    }

    public function blockSuspiciousIp($ip, $deviceId = null)
    {
        $command = $deviceId 
            ? "iptables -A OUTPUT -d {$ip} -j DROP"
            : "iptables -A FORWARD -d {$ip} -j DROP";
        
        // Execute firewall command
        shell_exec($command);
        
        return ['blocked' => true, 'ip' => $ip];
    }
}