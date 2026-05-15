<?php

namespace App\Services\Module15_RiskAssessment;

use App\Models\Module15_RiskAssessment\RiskAssessment;
use App\Models\Module15_RiskAssessment\RiskScore;

class RiskScoringService
{
    protected $likelihoodLevels = [
        1 => 'Very Low',
        2 => 'Low',
        3 => 'Medium',
        4 => 'High',
        5 => 'Very High'
    ];

    protected $impactLevels = [
        1 => 'Very Low',
        2 => 'Low',
        3 => 'Medium',
        4 => 'High',
        5 => 'Very High'
    ];

    public function calculateRiskScore($likelihood, $impact)
    {
        return $likelihood * $impact;
    }

    public function getRiskLevel($score)
    {
        if ($score <= 4) return ['level' => 'Low', 'color' => '#28a745', 'priority' => 1];
        if ($score <= 8) return ['level' => 'Medium', 'color' => '#ffc107', 'priority' => 2];
        if ($score <= 12) return ['level' => 'High', 'color' => '#fd7e14', 'priority' => 3];
        return ['level' => 'Critical', 'color' => '#dc3545', 'priority' => 4];
    }

    public function calculateInherentRisk($asset, $threats)
    {
        $score = 0;
        $count = 0;
        
        foreach ($threats as $threat) {
            $inherentLikelihood = $this->calculateInherentLikelihood($asset, $threat);
            $inherentImpact = $this->calculateInherentImpact($asset, $threat);
            $riskScore = $this->calculateRiskScore($inherentLikelihood, $inherentImpact);
            $score += $riskScore;
            $count++;
        }
        
        return $count > 0 ? $score / $count : 0;
    }

    public function calculateResidualRisk($asset, $controls)
    {
        $score = 0;
        $count = 0;
        
        foreach ($controls as $control) {
            $residualLikelihood = $this->calculateResidualLikelihood($asset, $control);
            $residualImpact = $this->calculateResidualImpact($asset, $control);
            $riskScore = $this->calculateRiskScore($residualLikelihood, $residualImpact);
            $score += $riskScore;
            $count++;
        }
        
        return $count > 0 ? $score / $count : 0;
    }

    protected function calculateInherentLikelihood($asset, $threat)
    {
        $factors = [
            'threat_capability' => $threat['capability'] ?? 3,
            'threat_intent' => $threat['intent'] ?? 3,
            'asset_exposure' => $asset['exposure'] ?? 3,
            'past_incidents' => $asset['past_incidents'] ?? 1
        ];
        
        return array_sum($factors) / count($factors);
    }

    protected function calculateInherentImpact($asset, $threat)
    {
        $factors = [
            'confidentiality' => $asset['confidentiality'] ?? 3,
            'integrity' => $asset['integrity'] ?? 3,
            'availability' => $asset['availability'] ?? 3,
            'financial_loss' => $asset['financial_value'] ?? 3
        ];
        
        return array_sum($factors) / count($factors);
    }

    protected function calculateResidualLikelihood($asset, $control)
    {
        $controlEffectiveness = $control['effectiveness'] ?? 1;
        $inherentLikelihood = $asset['inherent_likelihood'] ?? 3;
        
        return max(1, $inherentLikelihood - $controlEffectiveness);
    }

    protected function calculateResidualImpact($asset, $control)
    {
        $controlEffectiveness = $control['effectiveness'] ?? 1;
        $inherentImpact = $asset['inherent_impact'] ?? 3;
        
        return max(1, $inherentImpact - $controlEffectiveness);
    }

    public function saveRiskAssessment($data)
    {
        $riskScore = $this->calculateRiskScore($data['likelihood'], $data['impact']);
        $riskLevel = $this->getRiskLevel($riskScore);
        
        $assessment = RiskAssessment::create([
            'asset_id' => $data['asset_id'],
            'risk_name' => $data['risk_name'],
            'risk_description' => $data['description'],
            'risk_level' => $riskLevel['level'],
            'inherent_likelihood' => $data['likelihood'],
            'inherent_impact' => $data['impact'],
            'inherent_risk_score' => $riskScore,
            'assessment_date' => now(),
            'assessed_by' => auth()->id(),
            'status' => 'active'
        ]);
        
        RiskScore::create([
            'risk_assessment_id' => $assessment->id,
            'score_type' => 'inherent',
            'likelihood' => $data['likelihood'],
            'impact' => $data['impact'],
            'risk_value' => $riskScore,
            'risk_level' => $riskLevel['level'],
            'calculated_at' => now()
        ]);
        
        return $assessment;
    }

    public function getRiskMatrix()
    {
        $matrix = [];
        
        for ($likelihood = 1; $likelihood <= 5; $likelihood++) {
            for ($impact = 1; $impact <= 5; $impact++) {
                $score = $this->calculateRiskScore($likelihood, $impact);
                $level = $this->getRiskLevel($score);
                
                $matrix[$likelihood][$impact] = [
                    'score' => $score,
                    'level' => $level['level'],
                    'color' => $level['color']
                ];
            }
        }
        
        return $matrix;
    }
}