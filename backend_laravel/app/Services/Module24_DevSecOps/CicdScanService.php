<?php

namespace App\Services\Module24_DevSecOps;

use App\Models\Module24_DevSecOps\CicdScan;
use Illuminate\Support\Facades\Http;

class CicdScanService
{
    public function scanPipeline($pipelineData)
    {
        $scan = CicdScan::create([
            'pipeline_id' => $pipelineData['pipeline_id'],
            'repository' => $pipelineData['repository'],
            'branch' => $pipelineData['branch'],
            'commit_hash' => $pipelineData['commit_hash'],
            'scan_type' => $pipelineData['scan_type'],
            'tool_name' => $pipelineData['tool'] ?? 'custom',
            'scanned_at' => now()
        ]);
        
        $results = $this->executeScan($pipelineData);
        
        $scan->issues_found = $results['total_issues'];
        $scan->critical_count = $results['critical'];
        $scan->high_count = $results['high'];
        $scan->passed = $results['critical'] === 0;
        $scan->scan_duration = $results['duration'];
        $scan->save();
        
        if (!$scan->passed) {
            $this->failPipeline($scan);
        }
        
        return $scan;
    }

    protected function executeScan($pipelineData)
    {
        $startTime = microtime(true);
        $issues = [];
        
        // SAST scan
        if (in_array($pipelineData['scan_type'], ['sast', 'full'])) {
            $sastIssues = $this->runSastScan($pipelineData);
            $issues = array_merge($issues, $sastIssues);
        }
        
        // Secret scan
        if (in_array($pipelineData['scan_type'], ['secret', 'full'])) {
            $secretIssues = $this->runSecretScan($pipelineData);
            $issues = array_merge($issues, $secretIssues);
        }
        
        // Dependency scan
        if (in_array($pipelineData['scan_type'], ['dependency', 'full'])) {
            $depIssues = $this->runDependencyScan($pipelineData);
            $issues = array_merge($issues, $depIssues);
        }
        
        $duration = microtime(true) - $startTime;
        
        return [
            'total_issues' => count($issues),
            'critical' => count(array_filter($issues, fn($i) => $i['severity'] === 'critical')),
            'high' => count(array_filter($issues, fn($i) => $i['severity'] === 'high')),
            'medium' => count(array_filter($issues, fn($i) => $i['severity'] === 'medium')),
            'low' => count(array_filter($issues, fn($i) => $i['severity'] === 'low')),
            'duration' => round($duration, 2),
            'issues' => $issues
        ];
    }

    protected function runSastScan($pipelineData)
    {
        // Run SAST tool (Semgrep, CodeQL, etc.)
        return [];
    }

    protected function runSecretScan($pipelineData)
    {
        // Run secret detection (trufflehog, gitleaks)
        return [];
    }

    protected function runDependencyScan($pipelineData)
    {
        // Run dependency check (Snyk, OWASP DC)
        return [];
    }

    protected function failPipeline($scan)
    {
        // Call CI/CD API to fail the pipeline
        Http::post("{$scan->pipeline_id}/fail", [
            'reason' => 'Security scan failed',
            'scan_id' => $scan->id,
            'critical_findings' => $scan->critical_count
        ]);
    }

    public function getScanSummary($pipelineId)
    {
        $scans = CicdScan::where('pipeline_id', $pipelineId)
            ->orderBy('scanned_at', 'desc')
            ->get();
        
        return [
            'total_scans' => $scans->count(),
            'passing_scans' => $scans->where('passed', true)->count(),
            'failing_scans' => $scans->where('passed', false)->count(),
            'trend' => $this->calculateTrend($scans),
            'scans' => $scans
        ];
    }

    protected function calculateTrend($scans)
    {
        if ($scans->count() < 2) return 'stable';
        
        $recent = $scans->slice(0, 5)->avg('issues_found');
        $older = $scans->slice(5)->avg('issues_found');
        
        if ($recent < $older) return 'improving';
        if ($recent > $older) return 'declining';
        return 'stable';
    }
}