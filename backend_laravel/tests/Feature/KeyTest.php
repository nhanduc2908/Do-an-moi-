<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Module02_Encryption\EncryptionKey;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_encryption_key()
    {
        $this->actingAsAdmin();

        $response = $this->post('/api/keys', [
            'type' => 'AES',
            'size' => 256,
            'purpose' => 'encryption'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['key_id', 'fingerprint']);
    }

    public function test_user_can_list_encryption_keys()
    {
        EncryptionKey::factory()->count(3)->create();

        $response = $this->actingAsAdmin()
                         ->get('/api/keys');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_admin_can_revoke_key()
    {
        $this->actingAsAdmin();
        
        $key = EncryptionKey::factory()->create(['status' => 'active']);

        $response = $this->post("/api/keys/{$key->id}/revoke", [
            'reason' => 'compromised'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('revoked', $key->fresh()->status);
    }

    public function test_can_verify_key()
    {
        $key = EncryptionKey::factory()->create();

        $response = $this->post('/api/keys/verify', [
            'key_id' => $key->key_id,
            'signature' => 'test_signature'
        ]);

        $response->assertStatus(200);
    }
}