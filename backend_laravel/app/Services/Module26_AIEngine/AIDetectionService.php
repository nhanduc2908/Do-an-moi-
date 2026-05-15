<?php

namespace App\Services\Module26_AIEngine;

use App\Models\Module26_AIEngine\AIDetection;
use Illuminate\Support\Facades\Http;

class AIDetectionService
{
    protected $aiModelUrl = 'http://ai-engine:8080/predict';

    public function detectThreat($data)
    {
        $startTime = microtime(true);
        
        $response = Http::post($this->aiModelUrl, [
            'input' => $data
        ]);
        
        $processingTime = microtime(true) - $startTime;
        
        if ($response->successful()) {
            $result = $response->json();
            
            $detection = AIDetection::create([
                'detection_type' => $data['type'],
                'input_data' => $data,
                'confidence_score' => $result['confidence'],
                'prediction' => $result['prediction'],
                'model_version' => $result['model_version'],
                'processing_time_ms' => $processingTime * 1000,
                'detected_at' => now()
            ]);
            
            return $detection;
        }
        
        return null;
    }

    public function batchDetect($dataList)
    {
        $results = [];
        
        foreach ($dataList as $data) {
            $results[] = $this->detectThreat($data);
        }
        
        return $results;
    }

    public function getDetectionStats($hours = 24)
    {
        $detections = AIDetection::where('detected_at', '>=', now()->subHours($hours))->get();
        
        return [
            'total_detections' => $detections->count(),
            'anomalies_detected' => $detections->where('prediction', 'anomaly')->count(),
            'normal_traffic' => $detections->where('prediction', 'normal')->count(),
            'average_confidence' => $detections->avg('confidence_score'),
            'average_processing_time' => $detections->avg('processing_time_ms'),
            'by_type' => $detections->groupBy('detection_type')->map->count()
        ];
    }
}