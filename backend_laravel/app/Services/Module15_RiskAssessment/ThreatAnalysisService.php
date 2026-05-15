<?php

namespace App\Services\Module15_RiskAssessment;

use App\Models\Module15_RiskAssessment\Threat;

class ThreatAnalysisService
{
    protected $threatCategories = [
        'malware' => ['ransomware', 'trojan', 'worm', 'virus', 'spyware'],
        'network' => ['ddos', 'man_in_middle', 'sniffing', 'spoofing'],
        'web' => ['sql_injection', 'xss', 'csrf', 'rce'],
        'physical' => ['theft', 'damage', 'tampering', 'unauthorized_access'],
        'social' => ['phishing', 'pretexting', 'baiting', 'tailgating']
    ];

    public function analyzeThreats($assetId)
    {
        $threats = Threat::whereHas('assets', function($query) use ($assetId) {
            $query->where('asset_id', $assetId);
        })->get();
        
        $analysis = [
            'total_threats' => $threats->count(),
            'by_category' => [],
            'high_risk_threats' => [],
            'recommendations' => []
        ];
        
        foreach ($this->threatCategories as $category => $types) {
            $categoryThreats = $threats->filter(function($threat) use ($types) {
                return in_array(strtolower($threat->threat_name), $types);
            });
            
            $analysis['by_category'][$category] = [
                'count' => $categoryThreats->count(),
                'avg_likelihood' => $categoryThreats->avg('likelihood'),
                'avg_impact' => $categoryThreats->avg('impact')
            ];
            
            $highRisk = $categoryThreats->filter(function($threat) {
                return ($threat->likelihood * $threat->impact) >= 12;
            });
            
            foreach ($highRisk as $threat) {
                $analysis['high_risk_threats'][] = [
                    'name' => $threat->threat_name,
                    'category' => $category,
                    'risk_score' => $threat->likelihood * $threat->impact
                ];
            }
        }
        
        $analysis['recommendations'] = $this->generateRecommendations($analysis);
        
        return $analysis;
    }

    protected function generateRecommendations($analysis)
    {
        $recommendations = [];
        
        foreach ($analysis['by_category'] as $category => $data) {
            if ($data['avg_likelihood'] > 3) {
                $recommendations[] = "Implement controls to reduce {$category} threat likelihood";
            }
            
            if ($data['avg_impact'] > 3) {
                $recommendations[] = "Implement mitigation strategies for {$category} threats";
            }
        }
        
        if (count($analysis['high_risk_threats']) > 0) {
            $recommendations[] = "Immediate attention required for high-risk threats";
        }
        
        return $recommendations;
    }

    public function threatModeling($systemData)
    {
        $model = [
            'system_name' => $systemData['name'],
            'trust_boundaries' => $this->identifyTrustBoundaries($systemData),
            'data_flows' => $this->identifyDataFlows($systemData),
            'threats' => $this->identifyThreats($systemData),
            'countermeasures' => []
        ];
        
        foreach ($model['threats'] as $threat) {
            $model['countermeasures'][] = $this->suggestCountermeasure($threat);
        }
        
        return $model;
    }

    protected function identifyTrustBoundaries($systemData)
    {
        return $systemData['trust_boundaries'] ?? [];
    }

    protected function identifyDataFlows($systemData)
    {
        return $systemData['data_flows'] ?? [];
    }

    protected function identifyThreats($systemData)
    {
        $threats = [];
        
        // STRIDE threat modeling
        $stride = ['Spoofing', 'Tampering', 'Repudiation', 'Information Disclosure', 'DoS', 'Elevation of Privilege'];
        
        foreach ($stride as $threatType) {
            $threats[] = [
                'type' => $threatType,
                'description' => "Potential {$threatType} vulnerability",
                'severity' => 'medium'
            ];
        }
        
        return $threats;
    }

    protected function suggestCountermeasure($threat)
    {
        $countermeasures = [
            'Spoofing' => 'Implement strong authentication and authorization',
            'Tampering' => 'Use integrity checks and digital signatures',
            'Repudiation' => 'Implement comprehensive logging and auditing',
            'Information Disclosure' => 'Use encryption and access controls',
            'DoS' => 'Implement rate limiting and DDoS protection',
            'Elevation of Privilege' => 'Apply least privilege principle'
        ];
        
        return $countermeasures[$threat['type']] ?? 'Review security controls';
    }
}