<?php

namespace App\Services\Module13_LoggingMonitoring;

use App\Models\Module13_LoggingMonitoring\ThreatEvent;

class ThreatDetectionService
{
    protected $threatSignatures = [
        'sql_injection' => [
            'pattern' => '/(union|select|insert|delete|drop).*(from|into|where)/i',
            'severity' => 'high',
            'category' => 'injection'
        ],
        'xss' => [
            'pattern' => '/<script|<iframe|javascript:|onerror=/i',
            'severity' => 'medium',
            'category' => 'xss'
        ],
        'path_traversal' => [
            'pattern' => '/\.\.\/|\.\.\\\/',
            'severity' => 'medium',
            'category' => 'path_traversal'
        ],
        'command_injection' => [
            'pattern' => '/\|.*;|;.*\||\&\&.*\$|\$.*\&\&/',
            'severity' => 'critical',
            'category' => 'injection'
        ]
    ];

    public function detectThreat($requestData)
    {
        $detectedThreats = [];
        
        foreach ($this->threatSignatures as $threatType => $signature) {
            if ($this->matchesPattern($requestData, $signature['pattern'])) {
                $threat = ThreatEvent::create([
                    'threat_type' => $threatType,
                    'severity' => $signature['severity'],
                    'source_ip' => $requestData['ip'] ?? request()->ip(),
                    'destination_ip' => $requestData['target'] ?? null,
                    'attack_vector' => $requestData['path'] ?? request()->fullUrl(),
                    'payload' => $requestData['payload'] ?? $requestData,
                    'detected_at' => now(),
                    'mitigation_status' => 'detected'
                ]);
                
                $detectedThreats[] = $threat;
            }
        }
        
        return $detectedThreats;
    }

    protected function matchesPattern($data, $pattern)
    {
        $dataString = json_encode($data);
        return preg_match($pattern, $dataString) === 1;
    }

    public function getThreatIntelligence($hours = 24)
    {
        $since = now()->subHours($hours);
        
        return [
            'total_threats' => ThreatEvent::where('detected_at', '>=', $since)->count(),
            'by_type' => ThreatEvent::where('detected_at', '>=', $since)
                ->select('threat_type', \DB::raw('count(*) as count'))
                ->groupBy('threat_type')
                ->get(),
            'by_severity' => ThreatEvent::where('detected_at', '>=', $since)
                ->select('severity', \DB::raw('count(*) as count'))
                ->groupBy('severity')
                ->get(),
            'top_attackers' => ThreatEvent::where('detected_at', '>=', $since)
                ->select('source_ip', \DB::raw('count(*) as count'))
                ->groupBy('source_ip')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
        ];
    }
}