<?php

namespace App\Services\Module12_ContainerSecurity;

use Illuminate\Support\Facades\Process;

class ContainerRuntimeService
{
    public function monitorRunningContainers()
    {
        $result = Process::run("docker ps --format json");
        $containers = [];
        
        $lines = explode("\n", $result->output());
        foreach ($lines as $line) {
            if (!empty($line)) {
                $containers[] = json_decode($line, true);
            }
        }
        
        $securityStatus = [];
        foreach ($containers as $container) {
            $securityStatus[$container['ID']] = $this->assessContainerSecurity($container);
        }
        
        return $securityStatus;
    }

    protected function assessContainerSecurity($container)
    {
        $issues = [];
        
        // Check privileged mode
        if ($this->isPrivileged($container['ID'])) {
            $issues[] = [
                'type' => 'privileged_container',
                'severity' => 'critical',
                'description' => 'Container running in privileged mode'
            ];
        }
        
        // Check for host PID namespace
        if ($this->isHostPid($container['ID'])) {
            $issues[] = [
                'type' => 'host_pid',
                'severity' => 'high',
                'description' => 'Container shares host PID namespace'
            ];
        }
        
        // Check for read-only root filesystem
        if (!$this->isReadOnlyRoot($container['ID'])) {
            $issues[] = [
                'type' => 'writable_rootfs',
                'severity' => 'medium',
                'description' => 'Container root filesystem is writable'
            ];
        }
        
        return [
            'container_id' => $container['ID'],
            'name' => $container['Names'],
            'status' => $container['Status'],
            'security_issues' => $issues,
            'risk_score' => $this->calculateRiskScore($issues)
        ];
    }

    protected function isPrivileged($containerId)
    {
        $result = Process::run("docker inspect --format '{{.HostConfig.Privileged}}' {$containerId}");
        return trim($result->output()) === 'true';
    }

    protected function isHostPid($containerId)
    {
        $result = Process::run("docker inspect --format '{{.HostConfig.PidMode}}' {$containerId}");
        return trim($result->output()) === 'host';
    }

    protected function isReadOnlyRoot($containerId)
    {
        $result = Process::run("docker inspect --format '{{.HostConfig.ReadonlyRootfs}}' {$containerId}");
        return trim($result->output()) === 'true';
    }

    protected function calculateRiskScore($issues)
    {
        $severityWeights = [
            'critical' => 10,
            'high' => 5,
            'medium' => 2,
            'low' => 1
        ];
        
        $score = 0;
        foreach ($issues as $issue) {
            $score += $severityWeights[$issue['severity']] ?? 0;
        }
        
        return min($score, 100);
    }

    public function isolateContainer($containerId)
    {
        // Stop container
        Process::run("docker stop {$containerId}");
        
        // Move to quarantine network
        Process::run("docker network disconnect bridge {$containerId}");
        Process::run("docker network connect quarantine {$containerId}");
        
        return [
            'status' => 'isolated',
            'container_id' => $containerId,
            'isolated_at' => now()
        ];
    }
}