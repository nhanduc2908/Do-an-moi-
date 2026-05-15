<?php

namespace App\Services\Module15_RiskAssessment;

use App\Models\Module15_RiskAssessment\RiskAssessment;

class RiskMatrixService
{
    public function generateMatrix($assessments = null)
    {
        if (!$assessments) {
            $assessments = RiskAssessment::where('status', 'active')->get();
        }
        
        $matrix = [
            'rows' => ['Very Low', 'Low', 'Medium', 'High', 'Very High'],
            'columns' => ['Very Low', 'Low', 'Medium', 'High', 'Very High'],
            'data' => []
        ];
        
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                $matrix['data'][$i][$j] = [
                    'count' => 0,
                    'assessments' => [],
                    'risk_level' => (new RiskScoringService())->getRiskLevel($i * $j)
                ];
            }
        }
        
        foreach ($assessments as $assessment) {
            $likelihood = $assessment->inherent_likelihood;
            $impact = $assessment->inherent_impact;
            
            $matrix['data'][$likelihood][$impact]['count']++;
            $matrix['data'][$likelihood][$impact]['assessments'][] = $assessment;
        }
        
        return $matrix;
    }

    public function getHeatmapData()
    {
        $assessments = RiskAssessment::where('status', 'active')->get();
        $heatmap = [];
        
        foreach ($assessments as $assessment) {
            $key = "{$assessment->inherent_likelihood}_{$assessment->inherent_impact}";
            
            if (!isset($heatmap[$key])) {
                $heatmap[$key] = 0;
            }
            
            $heatmap[$key]++;
        }
        
        return $heatmap;
    }

    public function getRiskDistribution()
    {
        $assessments = RiskAssessment::where('status', 'active')->get();
        $distribution = [
            'critical' => 0,
            'high' => 0,
            'medium' => 0,
            'low' => 0
        ];
        
        $riskScoring = new RiskScoringService();
        
        foreach ($assessments as $assessment) {
            $level = $riskScoring->getRiskLevel($assessment->inherent_risk_score);
            $distribution[strtolower($level['level'])]++;
        }
        
        return $distribution;
    }
}