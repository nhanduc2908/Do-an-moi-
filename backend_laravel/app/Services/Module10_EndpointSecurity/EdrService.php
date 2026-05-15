<?php

namespace App\Services\Module10_EndpointSecurity;

use App\Models\Module10_EndpointSecurity\Endpoint;

class EdrService
{
    protected $behaviorRules = [];

    public function monitorProcess($processData, $endpointId)
    {
        $alerts = [];
        
        // Monitor suspicious processes
        if ($this->isSuspiciousProcess($processData)) {
            $alerts[] = $this->alertSuspiciousProcess($processData, $endpointId);
        }
        
        // Monitor parent-child process relationships
        if ($this->isAbnormalParentChild($processData)) {
            $alerts[] = $this->alertAbnormalProcess($processData, $endpointId);
        }
        
        // Monitor process network connections
        if ($this->isSuspiciousNetworkConnection($processData)) {
            $alerts[] = $this->alertNetworkConnection($processData, $endpointId);
        }
        
        return $alerts;
    }

    public function monitorFileSystem($fileEvent, $endpointId)
    {
        $alerts = [];
        
        // Monitor file modifications in sensitive locations
        $sensitivePaths = [
            '/etc/',
            '/system32/',
            '/windows/system32/',
            '/usr/bin/'
        ];
        
        foreach ($sensitivePaths as $path) {
            if (str_starts_with($fileEvent['path'], $path)) {
                $alerts[] = $this->alertSensitiveFileChange($fileEvent, $endpointId);
            }
        }
        
        // Monitor ransomware patterns (mass file modifications)
        if ($this->detectRansomwarePattern($fileEvent, $endpointId)) {
            $alerts[] = $this->alertRansomware($fileEvent, $endpointId);
        }
        
        return $alerts;
    }

    protected function isSuspiciousProcess($processData)
    {
        $suspiciousNames = [
            'powershell.exe', 'cmd.exe', 'wscript.exe', 'cscript.exe',
            'rundll32.exe', 'regsvr32.exe', 'mshta.exe'
        ];
        
        return in_array(strtolower($processData['name']), $suspiciousNames)
            && $processData['command_line'] && str_contains($processData['command_line'], '-enc');
    }

    protected function isAbnormalParentChild($processData)
    {
        // Check if Word/Excel spawning PowerShell
        if (in_array($processData['parent'], ['winword.exe', 'excel.exe']) 
            && $processData['name'] === 'powershell.exe') {
            return true;
        }
        
        return false;
    }

    protected function isSuspiciousNetworkConnection($processData)
    {
        // Check for connections to known malicious IPs
        $maliciousIps = ['185.130.5.253', '94.102.61.78'];
        return in_array($processData['destination_ip'], $maliciousIps);
    }

    protected function detectRansomwarePattern($fileEvent, $endpointId)
    {
        $recentEvents = \Cache::get("edr_file_events_{$endpointId}", []);
        $recentEvents[] = $fileEvent;
        \Cache::put("edr_file_events_{$endpointId}", array_slice($recentEvents, -100), 60);
        
        // Check for rapid file modifications
        if (count($recentEvents) > 50) {
            $timestamps = array_column($recentEvents, 'timestamp');
            $first = min($timestamps);
            $last = max($timestamps);
            $duration = strtotime($last) - strtotime($first);
            
            return $duration < 60; // 50+ file changes in 1 minute
        }
        
        return false;
    }

    protected function alertSuspiciousProcess($processData, $endpointId)
    {
        return [
            'type' => 'suspicious_process',
            'severity' => 'high',
            'process' => $processData['name'],
            'command_line' => $processData['command_line'],
            'endpoint_id' => $endpointId,
            'timestamp' => now()
        ];
    }

    protected function alertAbnormalProcess($processData, $endpointId)
    {
        return [
            'type' => 'abnormal_parent_child',
            'severity' => 'high',
            'parent' => $processData['parent'],
            'child' => $processData['name'],
            'endpoint_id' => $endpointId,
            'timestamp' => now()
        ];
    }

    protected function alertNetworkConnection($processData, $endpointId)
    {
        return [
            'type' => 'suspicious_network',
            'severity' => 'critical',
            'process' => $processData['name'],
            'destination_ip' => $processData['destination_ip'],
            'endpoint_id' => $endpointId,
            'timestamp' => now()
        ];
    }

    protected function alertSensitiveFileChange($fileEvent, $endpointId)
    {
        return [
            'type' => 'sensitive_file_change',
            'severity' => 'critical',
            'file_path' => $fileEvent['path'],
            'action' => $fileEvent['action'],
            'endpoint_id' => $endpointId,
            'timestamp' => now()
        ];
    }

    protected function alertRansomware($fileEvent, $endpointId)
    {
        return [
            'type' => 'ransomware_pattern',
            'severity' => 'critical',
            'file_count' => $fileEvent['count'],
            'endpoint_id' => $endpointId,
            'timestamp' => now(),
            'action_required' => 'immediate_isolation'
        ];
    }
}