<?php

namespace App\Services\Module26_AIEngine;

use App\Models\Module26_AIEngine\AIPrediction;

class AIPredictionService
{
    public function predictRisk($data)
    {
        // Predictive model for security risk
        $riskScore = $this->calculateRiskScore($data);
        $predictedIncidents = $this->predictIncidents($data);
        
        $prediction = AIPrediction::create([
            'prediction_type' => 'risk_score',
            'model_name' => 'risk_prediction_v2',
            'features' => $data,
            'predicted_value' => $riskScore,
            'accuracy' => 0.85,
            'predicted_at' => now()
        ]);
        
        return [
            'risk_score' => $riskScore,
            'risk_level' => $this->getRiskLevel($riskScore),
            'predicted_incidents' => $predictedIncidents,
            'confidence' => $this->calculatePredictionConfidence($data)
        ];
    }

    protected function calculateRiskScore($data)
    {
        $score = 0;
        $weights = [
            'past_incidents' => 0.3,
            'vulnerabilities_count' => 0.25,
            'security_controls' => 0.2,
            'compliance_score' => 0.15,
            'threat_intelligence' => 0.1
        ];
        
        foreach ($weights as $factor => $weight) {
            if (isset($data[$factor])) {
                $score += $data[$factor] * $weight;
            }
        }
        
        return min($score, 10);
    }

    protected function predictIncidents($data)
    {
        // Time series prediction for incident volume
        $baseRate = $data['past_incidents'] ?? 5;
        $trend = $data['trend'] ?? 0;
        
        return max(0, round($baseRate * (1 + $trend)));
    }

    protected function getRiskLevel($score)
    {
        if ($score >= 7) return 'Critical';
        if ($score >= 4) return 'High';
        if ($score >= 2) return 'Medium';
        return 'Low';
    }

    protected function calculatePredictionConfidence($data)
    {
        $confidence = 0.8;
        
        // Reduce confidence if data is sparse
        if (count($data) < 5) {
            $confidence -= 0.2;
        }
        
        return max(0.5, min(0.95, $confidence));
    }

    public function predictVulnerabilityTrend($historicalData)
    {
        // Predict vulnerability discovery trend
        $trend = $this->calculateTrend($historicalData);
        
        $prediction = AIPrediction::create([
            'prediction_type' => 'vulnerability_trend',
            'model_name' => 'trend_prediction',
            'features' => $historicalData,
            'predicted_value' => $trend,
            'accuracy' => 0.75,
            'predicted_at' => now()
        ]);
        
        return [
            'trend' => $trend > 0 ? 'increasing' : ($trend < 0 ? 'decreasing' : 'stable'),
            'expected_change' => abs($trend),
            'forecast' => $this->generateForecast($historicalData, $trend)
        ];
    }

    protected function calculateTrend($historicalData)
    {
        // Simple linear regression
        $n = count($historicalData);
        if ($n < 2) return 0;
        
        $x = range(1, $n);
        $y = $historicalData;
        
        $xMean = array_sum($x) / $n;
        $yMean = array_sum($y) / $n;
        
        $numerator = 0;
        $denominator = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $numerator += ($x[$i] - $xMean) * ($y[$i] - $yMean);
            $denominator += pow($x[$i] - $xMean, 2);
        }
        
        return $denominator != 0 ? $numerator / $denominator : 0;
    }

    protected function generateForecast($historicalData, $trend)
    {
        $lastValue = end($historicalData);
        $forecast = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $forecast[] = max(0, $lastValue + ($trend * $i));
        }
        
        return $forecast;
    }
}