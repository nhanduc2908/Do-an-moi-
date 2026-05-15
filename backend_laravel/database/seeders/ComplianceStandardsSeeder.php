<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module16_Compliance\ComplianceStandard;

class ComplianceStandardsSeeder extends Seeder
{
    public function run()
    {
        $standards = [
            ['standard_code' => 'ISO27001', 'standard_name' => 'ISO/IEC 27001:2022', 'version' => '2022', 'jurisdiction' => 'International', 'is_required' => true],
            ['standard_code' => 'GDPR', 'standard_name' => 'General Data Protection Regulation', 'version' => '2016/679', 'jurisdiction' => 'EU', 'is_required' => true],
            ['standard_code' => 'PCI_DSS', 'standard_name' => 'PCI DSS v4.0', 'version' => '4.0', 'jurisdiction' => 'International', 'is_required' => false],
            ['standard_code' => 'NIST_CSF', 'standard_name' => 'NIST Cybersecurity Framework', 'version' => '1.1', 'jurisdiction' => 'USA', 'is_required' => false],
        ];

        foreach ($standards as $standard) {
            ComplianceStandard::create($standard);
        }
    }
}