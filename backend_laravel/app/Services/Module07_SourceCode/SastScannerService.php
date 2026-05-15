<?php

namespace App\Services\Module07_SourceCode;

use App\Models\Module07_SourceCode\CodeScanResult;

class SastScannerService
{
    protected $vulnerabilityPatterns = [
        'sql_injection' => '/\b(select|insert|update|delete)\b.*\b(from|into)\b.*\$\w+/i',
        'xss' => '/echo\s*\(\s*\$_?(GET|POST|REQUEST)/i',
        'command_injection' => '/(exec|system|shell_exec|passthru)\s*\(\s*\$_/i',
        'file_inclusion' => '/(include|require)(_once)?\s*\(\s*\$_/i'
    ];

    public function scan($code, $filePath)
    {
        $findings = [];
        
        foreach ($this->vulnerabilityPatterns as $type => $pattern) {
            if (preg_match_all($pattern, $code, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[0] as $match) {
                    $findings[] = [
                        'type' => $type,
                        'line' => $this->getLineNumber($code, $match[1]),
                        'code' => $match[0],
                        'severity' => $this->getSeverity($type)
                    ];
                }
            }
        }

        return CodeScanResult::create([
            'repository' => $this->getRepository($filePath),
            'branch' => 'main',
            'commit_hash' => 'latest',
            'scan_tool' => 'SAST Scanner',
            'total_issues' => count($findings),
            'critical_count' => count(array_filter($findings, fn($f) => $f['severity'] === 'critical')),
            'high_count' => count(array_filter($findings, fn($f) => $f['severity'] === 'high')),
            'medium_count' => count(array_filter($findings, fn($f) => $f['severity'] === 'medium')),
            'low_count' => count(array_filter($findings, fn($f) => $f['severity'] === 'low')),
            'scanned_at' => now(),
            'status' => 'completed'
        ]);
    }

    protected function getLineNumber($code, $offset)
    {
        return substr_count(substr($code, 0, $offset), "\n") + 1;
    }

    protected function getSeverity($type)
    {
        $severities = [
            'sql_injection' => 'critical',
            'command_injection' => 'critical',
            'xss' => 'high',
            'file_inclusion' => 'high'
        ];
        
        return $severities[$type] ?? 'medium';
    }

    protected function getRepository($filePath)
    {
        return 'local_repository';
    }
}