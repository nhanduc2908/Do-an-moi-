<?php

namespace App\Services\Module09_NetworkSecurity;

use App\Models\Module09_NetworkSecurity\PortScan;
use Illuminate\Support\Facades\Process;

class NetworkScanService
{
    public function scanPorts($target, $portRange = '1-1000')
    {
        $startTime = microtime(true);
        
        $command = "nmap -p {$portRange} -T4 {$target}";
        $result = Process::run($command);
        
        $output = $result->output();
        $ports = $this->parseNmapOutput($output);
        
        $scan = PortScan::create([
            'target_ip' => $target,
            'scan_type' => 'port_scan',
            'open_ports' => $ports['open'],
            'closed_ports' => $ports['closed'],
            'filtered_ports' => $ports['filtered'],
            'scan_duration' => microtime(true) - $startTime,
            'scanned_at' => now()
        ]);
        
        return $scan;
    }

    public function scanNetwork($subnet)
    {
        $command = "nmap -sn {$subnet}";
        $result = Process::run($command);
        
        return $this->parseHostDiscovery($result->output());
    }

    protected function parseNmapOutput($output)
    {
        $open = [];
        $closed = [];
        $filtered = [];
        
        $lines = explode("\n", $output);
        foreach ($lines as $line) {
            if (preg_match('/^(\d+)\/(tcp|udp)\s+(\w+)/', $line, $matches)) {
                $port = $matches[1];
                $state = $matches[3];
                
                if ($state === 'open') {
                    $open[] = (int)$port;
                } elseif ($state === 'closed') {
                    $closed[] = (int)$port;
                } elseif ($state === 'filtered') {
                    $filtered[] = (int)$port;
                }
            }
        }
        
        return [
            'open' => $open,
            'closed' => $closed,
            'filtered' => $filtered
        ];
    }

    protected function parseHostDiscovery($output)
    {
        $hosts = [];
        $lines = explode("\n", $output);
        
        foreach ($lines as $line) {
            if (preg_match('/Nmap scan report for (.+)/', $line, $matches)) {
                $hosts[] = $matches[1];
            }
        }
        
        return $hosts;
    }
}