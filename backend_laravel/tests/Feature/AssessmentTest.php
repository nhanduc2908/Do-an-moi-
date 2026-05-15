<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Module15_RiskAssessment\RiskAssessment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssessmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_assessment()
    {
        $this->actingAsManager();

        $response = $this->post('/api/assessments', [
            'assessment_type' => 'security',
            'target_system_id' => 'sys-123',
            'scope' => ['network', 'application']
        ]);

        $response->assertStatus(201);
    }

    public function test_can_submit_assessment()
    {
        $this->actingAsManager();
        
        $assessment = RiskAssessment::factory()->create(['status' => 'in_progress']);

        $response = $this->post("/api/assessments/{$assessment->id}/submit", [
            'answers' => [
                ['question_id' => 'q1', 'response' => 'Yes'],
                ['question_id' => 'q2', 'response' => 'No']
            ]
        ]);

        $response->assertStatus(200);
    }

    public function test_can_get_assessment_progress()
    {
        $assessment = RiskAssessment::factory()->create(['progress' => 50]);

        $response = $this->actingAsManager()
                         ->get("/api/assessments/{$assessment->id}/progress");

        $response->assertStatus(200)
                 ->assertJson(['progress' => 50]);
    }

    public function test_can_export_assessment()
    {
        $assessment = RiskAssessment::factory()->create();

        $response = $this->actingAsManager()
                         ->get("/api/assessments/{$assessment->id}/export");

        $response->assertStatus(200);
    }
}