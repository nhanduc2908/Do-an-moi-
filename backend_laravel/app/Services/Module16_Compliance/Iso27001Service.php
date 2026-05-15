<?php

namespace App\Services\Module16_Compliance;

use App\Models\Module16_Compliance\ComplianceCheck;
use App\Models\Module16_Compliance\ComplianceStandard;

class Iso27001Service
{
    protected $controls = [
        'A.5' => 'Information security policies',
        'A.6' => 'Organization of information security',
        'A.7' => 'Human resource security',
        'A.8' => 'Asset management',
        'A.9' => 'Access control',
        'A.10' => 'Cryptography',
        'A.11' => 'Physical and environmental security',
        'A.12' => 'Operations security',
        'A.13' => 'Communications security',
        'A.14' => 'System acquisition, development and maintenance',
        'A.15' => 'Supplier relationships',
        'A.16' => 'Information security incident management',
        'A.17' => 'Information security aspects of business continuity management',
        'A.18' => 'Compliance'
    ];

    public function getComplianceStatus()
    {
        $standard = ComplianceStandard::where('standard_code', 'ISO27001')->first();
        
        if (!$standard) {
            $standard = $this->createStandard();
        }
        
        $checks = ComplianceCheck::where('standard_id', $standard->id)->get();
        
        $status = [
            'standard' => 'ISO 27001:2022',
            'total_controls' => count($this->controls),
            'implemented' => $checks->where('status', 'passed')->count(),
            'partial' => $checks->where('status', 'partial')->count(),
            'not_implemented' => $checks->where('status', 'failed')->count(),
            'compliance_percentage' => 0,
            'controls_status' => []
        ];
        
        if ($status['total_controls'] > 0) {
            $status['compliance_percentage'] = ($status['implemented'] / $status['total_controls']) * 100;
        }
        
        foreach ($this->controls as $code => $name) {
            $check = $checks->where('control_code', $code)->first();
            $status['controls_status'][$code] = [
                'name' => $name,
                'status' => $check ? $check->status : 'not_started',
                'score' => $check ? $check->score : 0,
                'evidence' => $check ? $check->evidence_path : null
            ];
        }
        
        return $status;
    }

    protected function createStandard()
    {
        return ComplianceStandard::create([
            'standard_code' => 'ISO27001',
            'standard_name' => 'ISO/IEC 27001:2022',
            'version' => '2022',
            'jurisdiction' => 'International',
            'description' => 'Information Security Management System',
            'is_required' => true,
            'effective_date' => '2022-10-25'
        ]);
    }

    public function generateStatementOfApplicability()
    {
        $status = $this->getComplianceStatus();
        
        $soa = [
            'organization' => config('app.name'),
            'date' => now(),
            'controls' => []
        ];
        
        foreach ($status['controls_status'] as $code => $control) {
            $soa['controls'][] = [
                'control_id' => $code,
                'control_name' => $control['name'],
                'applicable' => $control['status'] !== 'excluded',
                'status' => $control['status'],
                'justification' => $this->getJustification($control['status'])
            ];
        }
        
        return $soa;
    }

    protected function getJustification($status)
    {
        switch ($status) {
            case 'passed':
                return 'Control is fully implemented and effective';
            case 'partial':
                return 'Control is partially implemented, remediation in progress';
            case 'failed':
                return 'Control not implemented, action plan required';
            default:
                return 'Control applicability under review';
        }
    }
}