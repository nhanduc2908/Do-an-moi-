<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Module16_Compliance\Iso27001Service;
use App\Services\Module16_Compliance\GdprService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComplianceTest extends TestCase
{
    use RefreshDatabase;

    public function test_iso27001_compliance_status()
    {
        $service = new Iso27001Service();
        $status = $service->getComplianceStatus();
        
        $this->assertArrayHasKey('total_controls', $status);
        $this->assertArrayHasKey('compliance_percentage', $status);
    }

    public function test_gdpr_compliance_check()
    {
        $service = new GdprService();
        $status = $service->getComplianceStatus();
        
        $this->assertArrayHasKey('total_articles', $status);
        $this->assertArrayHasKey('articles_status', $status);
    }

    public function test_statement_of_applicability_generation()
    {
        $service = new Iso27001Service();
        $soa = $service->generateStatementOfApplicability();
        
        $this->assertArrayHasKey('controls', $soa);
    }

    public function test_dpia_generation()
    {
        $service = new GdprService();
        $dpia = $service->generateDpia([
            'name' => 'Customer Data Processing',
            'special_category' => 'Yes'
        ]);
        
        $this->assertArrayHasKey('sections', $dpia);
    }
}