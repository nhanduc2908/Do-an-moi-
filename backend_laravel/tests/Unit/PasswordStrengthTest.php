<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Module04_PasswordSecurity\PasswordStrengthService;

class PasswordStrengthTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PasswordStrengthService();
    }

    public function test_weak_password_detection()
    {
        $result = $this->service->checkStrength('password');
        
        $this->assertEquals('Very Weak', $result['strength']);
        $this->assertLessThan(30, $result['score']);
    }

    public function test_strong_password_detection()
    {
        $result = $this->service->checkStrength('P@ssw0rd123!');
        
        $this->assertEquals('Strong', $result['strength']);
        $this->assertGreaterThan(70, $result['score']);
    }

    public function test_password_with_sequential_chars_detection()
    {
        $result = $this->service->checkStrength('Password123456');
        
        $this->assertContains('Contains sequential characters', $result['feedback']);
    }

    public function test_password_with_repeated_chars_detection()
    {
        $result = $this->service->checkStrength('aaaabbbbcccc');
        
        $this->assertContains('Contains repeated characters', $result['feedback']);
    }

    public function test_very_strong_password()
    {
        $result = $this->service->checkStrength('Xy9#kL8$mN2@qR5!');
        
        $this->assertEquals('Very Strong', $result['strength']);
        $this->assertGreaterThan(85, $result['score']);
    }
}