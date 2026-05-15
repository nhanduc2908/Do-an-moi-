<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Module26_AIEngine\AIChatbotService;
use App\Services\Module26_AIEngine\AICriteriaGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AITest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_intent_detection()
    {
        $service = new AIChatbotService();
        
        $intent = $service->detectIntent('Can you scan for vulnerabilities?');
        
        $this->assertEquals('vulnerability_scan', $intent['intent']);
    }

    public function test_criteria_generation()
    {
        $service = new AICriteriaGeneratorService();
        
        $suggestion = $service->generateCriteria('security', 'We need strong access control policies');
        
        $this->assertNotEmpty($suggestion->suggested_criteria);
        $this->assertGreaterThan(0, $suggestion->confidence_score);
    }

    public function test_anomaly_detection()
    {
        $service = new \App\Services\Module26_AIEngine\AIAnomalyService();
        
        $anomaly = $service->detectAnomaly([
            'login_attempts' => 150,
            'traffic_volume' => 50000,
            'failed_logins' => 120
        ]);
        
        $this->assertNotNull($anomaly);
        $this->assertGreaterThan(0, $anomaly->anomaly_score);
    }

    public function test_threat_detection()
    {
        $service = new \App\Services\Module26_AIEngine\AIDetectionService();
        
        $detection = $service->detectThreat([
            'type' => 'network_traffic',
            'data' => ['packet_size' => 65535, 'protocol' => 'tcp']
        ]);
        
        $this->assertNotNull($detection);
    }
}