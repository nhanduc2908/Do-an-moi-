<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Module14_IncidentResponse\Incident;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncidentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_incident()
    {
        $this->actingAsManager();

        $response = $this->post('/api/incidents', [
            'title' => 'Security Breach Detected',
            'description' => 'Unauthorized access detected',
            'severity' => 'high',
            'category' => 'unauthorized_access'
        ]);

        $response->assertStatus(201);
    }

    public function test_can_list_incidents()
    {
        Incident::factory()->count(5)->create();

        $response = $this->actingAsManager()
                         ->get('/api/incidents');

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }

    public function test_can_resolve_incident()
    {
        $incident = Incident::factory()->create(['status' => 'open']);

        $response = $this->actingAsManager()
                         ->post("/api/incidents/{$incident->id}/resolve", [
                             'resolution_summary' => 'Issue fixed',
                             'root_cause' => 'Misconfiguration'
                         ]);

        $response->assertStatus(200);
        $this->assertEquals('resolved', $incident->fresh()->status);
    }

    public function test_can_add_comment_to_incident()
    {
        $incident = Incident::factory()->create();

        $response = $this->actingAsManager()
                         ->post("/api/incidents/{$incident->id}/comment", [
                             'comment' => 'Investigating this issue'
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('incident_comments', [
            'incident_id' => $incident->id,
            'comment' => 'Investigating this issue'
        ]);
    }
}