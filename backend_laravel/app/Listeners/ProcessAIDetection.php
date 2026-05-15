<?php

namespace App\Listeners;

use App\Events\ThreatDetected;
use App\Services\Module26_AIEngine\AIDetectionService;
use App\Jobs\SendSecurityAlertJob;

class ProcessAIDetection
{
    protected $aiService;

    public function __construct(AIDetectionService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function handle(ThreatDetected $event)
    {
        // Run AI detection on threat data
        $detection = $this->aiService->detectThreat([
            'type' => $event->threatType,
            'data' => $event->threatData
        ]);
        
        if ($detection && $detection->confidence_score > 0.8) {
            $alertData = [
                'type' => 'ai_threat_detection',
                'severity' => 'high',
                'threat_type' => $detection->prediction,
                'confidence' => $detection->confidence_score,
                'details' => $detection->input_data
            ];
            
            $recipients = config('alert.ai_recipients', []);
            dispatch(new SendSecurityAlertJob($recipients, $alertData));
        }
    }
}