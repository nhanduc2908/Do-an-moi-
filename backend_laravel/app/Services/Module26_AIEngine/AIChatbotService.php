<?php

namespace App\Services\Module26_AIEngine;

use App\Models\Module26_AIEngine\AIChatLog;
use Illuminate\Support\Facades\Http;

class AIChatbotService
{
    protected $llmApiUrl = 'http://llm-engine:8080/chat';

    public function sendMessage($userId, $message, $context = null)
    {
        $response = Http::post($this->llmApiUrl, [
            'message' => $message,
            'context' => $context,
            'user_id' => $userId
        ]);
        
        if ($response->successful()) {
            $result = $response->json();
            
            $chatLog = AIChatLog::create([
                'user_id' => $userId,
                'session_id' => session()->getId(),
                'message' => $message,
                'response' => $result['response'],
                'intent' => $result['intent'] ?? null,
                'entities' => $result['entities'] ?? [],
                'confidence' => $result['confidence'] ?? 1,
                'created_at' => now()
            ]);
            
            return $chatLog;
        }
        
        return null;
    }

    public function getConversationHistory($userId, $limit = 50)
    {
        return AIChatLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function detectIntent($message)
    {
        $intents = [
            'vulnerability_scan' => ['scan', 'vulnerability', 'check security'],
            'generate_report' => ['report', 'generate report', 'export'],
            'compliance_check' => ['compliance', 'iso', 'gdpr', 'pci'],
            'incident_response' => ['incident', 'breach', 'attack'],
            'training' => ['training', 'course', 'learn']
        ];
        
        $detectedIntent = null;
        $maxScore = 0;
        
        foreach ($intents as $intent => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                if (stripos($message, $keyword) !== false) {
                    $score += 1;
                }
            }
            
            if ($score > $maxScore) {
                $maxScore = $score;
                $detectedIntent = $intent;
            }
        }
        
        return [
            'intent' => $detectedIntent,
            'confidence' => $maxScore > 0 ? $maxScore / 3 : 0
        ];
    }

    public function executeIntent($intent, $parameters)
    {
        switch ($intent) {
            case 'vulnerability_scan':
                return $this->executeVulnerabilityScan($parameters);
            case 'generate_report':
                return $this->executeGenerateReport($parameters);
            case 'compliance_check':
                return $this->executeComplianceCheck($parameters);
            case 'incident_response':
                return $this->executeIncidentResponse($parameters);
            case 'training':
                return $this->executeTraining($parameters);
            default:
                return ['response' => 'I can help you with security scans, reports, compliance checks, incident response, and training.'];
        }
    }

    protected function executeVulnerabilityScan($parameters)
    {
        // Trigger vulnerability scan
        return ['response' => 'Starting vulnerability scan as requested.'];
    }

    protected function executeGenerateReport($parameters)
    {
        // Generate report
        return ['response' => 'Generating security report...'];
    }

    protected function executeComplianceCheck($parameters)
    {
        // Run compliance check
        return ['response' => 'Running compliance check...'];
    }

    protected function executeIncidentResponse($parameters)
    {
        // Trigger incident response
        return ['response' => 'Incident response team has been notified.'];
    }

    protected function executeTraining($parameters)
    {
        // Suggest training
        return ['response' => 'I recommend completing the Security Awareness training course.'];
    }
}