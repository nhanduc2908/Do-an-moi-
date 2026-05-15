<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Module06_ApiSecurity\ApiAuthService;
use App\Services\Module06_ApiSecurity\RateLimitService;
use App\Models\Module06_ApiSecurity\ApiKey;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_key_generation()
    {
        $service = new ApiAuthService();
        
        $apiKey = $service->generateApiKey(1, 'Test Key', ['read', 'write']);
        
        $this->assertNotNull($apiKey);
        $this->assertEquals('Test Key', $apiKey->name);
    }

    public function test_api_key_validation()
    {
        $service = new ApiAuthService();
        
        $generated = $service->generateApiKey(1, 'Test Key');
        $validated = $service->validateApiKey($generated->key);
        
        $this->assertNotNull($validated);
    }

    public function test_rate_limiting()
    {
        $service = new RateLimitService();
        
        for ($i = 0; $i < 60; $i++) {
            $result = $service->checkLimit('test_key', 60, 1);
            $this->assertTrue($result['allowed']);
        }
        
        $result = $service->checkLimit('test_key', 60, 1);
        $this->assertFalse($result['allowed']);
    }

    public function test_api_key_revocation()
    {
        $service = new ApiAuthService();
        
        $apiKey = $service->generateApiKey(1, 'Test Key');
        $service->revokeApiKey($apiKey->id);
        
        $validated = $service->validateApiKey($apiKey->key);
        $this->assertNull($validated);
    }
}