<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Module02_Encryption\KeyVaultService;
use App\Models\Module02_Encryption\EncryptionKey;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_key_storage_and_retrieval()
    {
        $service = new KeyVaultService();
        
        $keyData = [
            'key_material' => 'test_secret_key_12345678',
            'type' => 'AES',
            'size' => 256,
            'purpose' => 'encryption'
        ];
        
        $storedKey = $service->storeKey($keyData);
        $retrievedKey = $service->getKey($storedKey->key_id);
        
        $this->assertEquals($keyData['key_material'], $retrievedKey);
    }

    public function test_key_rotation()
    {
        $service = new KeyVaultService();
        
        $oldKey = $service->storeKey([
            'key_material' => 'old_key_value',
            'type' => 'AES',
            'size' => 256,
            'purpose' => 'encryption'
        ]);
        
        $newKey = $service->rotateKey($oldKey->key_id, [
            'type' => 'AES',
            'size' => 256,
            'purpose' => 'encryption'
        ]);
        
        $this->assertNotEquals($oldKey->key_id, $newKey->key_id);
        $this->assertEquals('revoked', $oldKey->fresh()->status);
    }

    public function test_key_revocation()
    {
        $service = new KeyVaultService();
        
        $key = $service->storeKey([
            'key_material' => 'test_key',
            'type' => 'AES',
            'size' => 256,
            'purpose' => 'encryption'
        ]);
        
        $service->revokeKey($key->key_id, 'testing');
        
        $this->assertEquals('revoked', $key->fresh()->status);
    }
}