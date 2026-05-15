<?php

namespace App\Services\Module12_ContainerSecurity;

use Illuminate\Support\Facades\Process;

class KubernetesScanService
{
    public function scanKubeconfig($kubeconfigPath)
    {
        $issues = [];
        $config = file_get_contents($kubeconfigPath);
        
        // Check for insecure connections
        if (str_contains($config, 'insecure-skip-tls-verify: true')) {
            $issues[] = [
                'type' => 'insecure_tls',
                'severity' => 'critical',
                'description' => 'TLS verification is disabled'
            ];
        }
        
        // Check for admin context
        if (str_contains($config, 'admin') && str_contains($config, 'system:masters')) {
            $issues[] = [
                'type' => 'admin_context',
                'severity' => 'high',
                'description' => 'Using admin context in kubeconfig'
            ];
        }
        
        return $issues;
    }

    public function scanPodSecurity($namespace = 'default')
    {
        // Run kube-bench or similar tool
        $result = Process::run("kube-bench run --targets node --check 1.2.1,1.2.2");
        
        if ($result->successful()) {
            return $this->parseKubeBenchOutput($result->output());
        }
        
        return [];
    }

    protected function parseKubeBenchOutput($output)
    {
        $findings = [];
        $lines = explode("\n", $output);
        
        foreach ($lines as $line) {
            if (preg_match('/\[FAIL\].*\[(\d+\.\d+\.\d+)\]/', $line, $matches)) {
                $findings[] = [
                    'check_id' => $matches[1],
                    'status' => 'failed',
                    'recommendation' => $line
                ];
            }
        }
        
        return $findings;
    }

    public function getNetworkPolicies($namespace)
    {
        $result = Process::run("kubectl get networkpolicies -n {$namespace} -o json");
        
        if ($result->successful()) {
            return json_decode($result->output(), true);
        }
        
        return ['items' => []];
    }
}