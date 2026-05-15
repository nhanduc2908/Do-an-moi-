<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Module03_WebSecurity\SqlInjectionDetector;

class SqlInjectionDetectorTest extends TestCase
{
    protected $detector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detector = new SqlInjectionDetector();
    }

    public function test_detects_basic_sql_injection()
    {
        $input = "' OR '1'='1";
        $result = $this->detector->detect($input);
        
        $this->assertTrue($result['detected']);
    }

    public function test_detects_union_select_injection()
    {
        $input = "1 UNION SELECT username, password FROM users";
        $result = $this->detector->detect($input);
        
        $this->assertTrue($result['detected']);
    }

    public function test_detects_drop_table_injection()
    {
        $input = "'; DROP TABLE users; --";
        $result = $this->detector->detect($input);
        
        $this->assertTrue($result['detected']);
    }

    public function test_detects_time_based_injection()
    {
        $input = "1' AND SLEEP(5) --";
        $result = $this->detector->detect($input);
        
        $this->assertTrue($result['detected']);
    }

    public function test_sanitize_removes_sql_keywords()
    {
        $input = "SELECT * FROM users WHERE id = 1";
        $sanitized = $this->detector->sanitize($input);
        
        $this->assertStringNotContainsString('SELECT', $sanitized);
    }

    public function test_safe_input_passes()
    {
        $input = "Normal search query";
        $result = $this->detector->detect($input);
        
        $this->assertFalse($result['detected']);
    }
}