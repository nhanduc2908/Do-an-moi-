<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Module03_WebSecurity\SqlInjectionDetector;
use App\Services\Module03_WebSecurity\XssDetector;

class WebSecurityTest extends TestCase
{
    public function test_sql_injection_detection()
    {
        $detector = new SqlInjectionDetector();
        
        $maliciousInput = "' OR '1'='1";
        $result = $detector->detect($maliciousInput);
        
        $this->assertTrue($result['detected']);
    }

    public function test_safe_input_passes_sql_injection_check()
    {
        $detector = new SqlInjectionDetector();
        
        $safeInput = "Normal user input";
        $result = $detector->detect($safeInput);
        
        $this->assertFalse($result['detected']);
    }

    public function test_xss_detection()
    {
        $detector = new XssDetector();
        
        $maliciousInput = "<script>alert('XSS')</script>";
        $result = $detector->detect($maliciousInput);
        
        $this->assertTrue($result['detected']);
    }

    public function test_xss_sanitization()
    {
        $detector = new XssDetector();
        
        $maliciousInput = "<script>alert('XSS')</script>";
        $sanitized = $detector->sanitize($maliciousInput);
        
        $this->assertStringNotContainsString('<script>', $sanitized);
    }
}