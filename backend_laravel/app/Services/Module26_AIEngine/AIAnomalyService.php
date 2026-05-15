<?php

namespace App\Services\Module26_AIEngine;

use App\Models\Module26_AIEngine\AIAnomaly;

class AIAnomalyService
{
    protected $anomalyThreshold = 0.85;

    public function detectAnomaly($dataPoint)
    {
        // Calculate anomaly score using statistical methods
        $score = $this->calculateAnomalyScore($dataPoint);
        
        $isAnomaly = $score > $this->anomalyThreshold;
        
        if ($isAnomaly) {
            $anomaly = AIAnomaly::create([
                'anomaly_score' => $score,
                'feature_importance' => $this->getFeatureImportance($dataPoint),
                'threshold' => $this->anomalyThreshold,
                'data_point' => $dataPoint,
                'anomaly_type' => $this->classifyAnomaly($dataPoint)
            ]);
            
            return $anomaly;
        }
        
        return null;
    }

    protected function calculateAnomalyScore($dataPoint)
    {
        // Isolation Forest or other anomaly detection algorithm
        $score = 0;
        
        // Example: Z-score based detection
        $values = array_values($dataPoint);
        $mean = array_sum($values) / count($values);
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values)) / count($values);
        
        $stdDev = sqrt($variance);
        
        foreach ($values as $value) {
            $zScore = abs(($value - $mean) / $stdDev);
            if ($zScore > 3) {
                $score += 0.2;
            }
        }
        
        return min($score, 1);
    }

    protected function getFeatureImportance($dataPoint)
    {
        $importance = [];
        $values = array_values($dataPoint);
        $mean = array_sum($values) / count($values);
        
        foreach ($dataPoint as $key => $value) {
            $importance[$key] = abs($value - $mean) / $mean;
        }
        
        arsort($importance);
        
        return $importance;
    }

    protected function classifyAnomaly($dataPoint)
    {
        // Classify anomaly type (e.g., traffic spike, unusual login, etc.)
        if (isset($dataPoint['login_attempts']) && $dataPoint['login_attempts'] > 100) {
            return 'brute_force';
        }
        
        if (isset($dataPoint['traffic_volume']) && $dataPoint['traffic_volume'] > 1000000) {
            return 'traffic_spike';
        }
        
        return 'unknown';
    }

    public function getAnomalyReport($hours = 24)
    {
        $anomalies = AIAnomaly::where('created_at', '>=', now()->subHours($hours))->get();
        
        return [
            'total_anomalies' => $anomalies->count(),
            'by_type' => $anomalies->groupBy('anomaly_type')->map->count(),
            'average_score' => $anomalies->avg('anomaly_score'),
            'top_features' => $this->getTopFeatures($anomalies),
            'timeline' => $anomalies->groupBy(function($a) {
                return $a->created_at->format('Y-m-d H:00');
            })->map->count()
        ];
    }

    protected function getTopFeatures($anomalies)
    {
        $features = [];
        
        foreach ($anomalies as $anomaly) {
            foreach ($anomaly->feature_importance as $feature => $importance) {
                if (!isset($features[$feature])) {
                    $features[$feature] = 0;
                }
                $features[$feature] += $importance;
            }
        }
        
        arsort($features);
        
        return array_slice($features, 0, 10, true);
    }
}