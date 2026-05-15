<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Module15_RiskAssessment\RiskScoringService;

class RiskScoringTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RiskScoringService();
    }

    public function test_calculate_risk_score()
    {
        $score = $this->service->calculateRiskScore(5, 5);
        $this->assertEquals(25, $score);
    }

    public function test_get_risk_level_low()
    {
        $level = $this->service->getRiskLevel(4);
        $this->assertEquals('Low', $level['level']);
    }

    public function test_get_risk_level_medium()
    {
        $level = $this->service->getRiskLevel(6);
        $this->assertEquals('Medium', $level['level']);
    }

    public function test_get_risk_level_high()
    {
        $level = $this->service->getRiskLevel(10);
        $this->assertEquals('High', $level['level']);
    }

    public function test_get_risk_level_critical()
    {
        $level = $this->service->getRiskLevel(15);
        $this->assertEquals('Critical', $level['level']);
    }

    public function test_calculate_inherent_risk()
    {
        $risk = $this->service->calculateInherentRisk(
            ['exposure' => 4, 'past_incidents' => 3],
            [['capability' => 4, 'intent' => 5]]
        );
        
        $this->assertIsFloat($risk);
    }

    public function test_risk_matrix_generation()
    {
        $matrix = $this->service->getRiskMatrix();
        
        $this->assertIsArray($matrix);
        $this->assertArrayHasKey(1, $matrix);
        $this->assertArrayHasKey(5, $matrix[5]);
    }
}