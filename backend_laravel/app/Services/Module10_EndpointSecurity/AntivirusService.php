<?php

namespace App\Services\Module10_EndpointSecurity;

use App\Models\Module10_EndpointSecurity\AntivirusLog;

class AntivirusService
{
    protected $virusSignatures = [];
    protected $heuristicRules = [];

    public function scanFile($filePath, $endpointId)
    {
        $content = file_get_contents($filePath);
        $threats = [];
        
        // Signature-based detection
        foreach ($this->virusSignatures as $signature) {
            if (str_contains($content, $signature['pattern'])) {
                $threats[] = [
                    'name' => $signature['name'],
                    'type' => $signature['type'],
                    'detection' => 'signature'
                ];
            }
        }
        
        // Heuristic detection
        if ($this->heuristicScan($content)) {
            $threats[] = [
                'name' => 'Suspicious behavior detected',
                'type' => 'heuristic',
                'detection' => 'heuristic'
            ];
        }
        
        if (!empty($threats)) {
            return $this->handleThreat($filePath, $threats, $endpointId);
        }
        
        return ['status' => 'clean'];
    }

    protected function heuristicScan($content)
    {
        $score = 0;
        
        // Check for suspicious patterns
        if (preg_match('/eval\s*\(/i', $content)) $score += 20;
        if (preg_match('/base64_decode\s*\(/i', $content)) $score += 15;
        if (preg_match('/system\s*\(/i', $content)) $score += 25;
        if (preg_match('/exec\s*\(/i', $content)) $score += 25;
        
        // Check entropy (packed code)
        $entropy = $this->calculateEntropy($content);
        if ($entropy > 7.5) $score += 30;
        
        return $score >= 50;
    }

    protected function calculateEntropy($data)
    {
        $length = strlen($data);
        if ($length === 0) return 0;
        
        $frequencies = array_fill(0, 256, 0);
        for ($i = 0; $i < $length; $i++) {
            $frequencies[ord($data[$i])]++;
        }
        
        $entropy = 0;
        foreach ($frequencies as $freq) {
            if ($freq > 0) {
                $p = $freq / $length;
                $entropy -= $p * log($p, 2);
            }
        }
        
        return $entropy;
    }

    protected function handleThreat($filePath, $threats, $endpointId)
    {
        $quarantinePath = storage_path('quarantine/' . basename($filePath));
        rename($filePath, $quarantinePath);
        
        foreach ($threats as $threat) {
            AntivirusLog::create([
                'endpoint_id' => $endpointId,
                'threat_name' => $threat['name'],
                'threat_type' => $threat['type'],
                'file_path' => $filePath,
                'action_taken' => 'quarantined',
                'status' => 'resolved',
                'detected_at' => now(),
                'resolved_at' => now()
            ]);
        }
        
        return [
            'status' => 'threat_detected',
            'threats' => $threats,
            'action' => 'quarantined'
        ];
    }
}