<?php

return [
    'standards' => [
        'iso27001' => 'ISO/IEC 27001:2022',
        'gdpr' => 'GDPR',
        'pci_dss' => 'PCI DSS v4.0',
        'hipaa' => 'HIPAA',
        'soc2' => 'SOC 2 Type II',
        'nist_csf' => 'NIST Cybersecurity Framework',
    ],
    'status' => [
        'compliant' => 'Compliant',
        'partial' => 'Partially Compliant',
        'non_compliant' => 'Non-Compliant',
        'not_applicable' => 'Not Applicable',
        'in_review' => 'Under Review',
        'remediation' => 'Remediation in Progress',
    ],
    'controls' => [
        'implemented' => 'Implemented',
        'not_implemented' => 'Not Implemented',
        'partially_implemented' => 'Partially Implemented',
        'planned' => 'Planned',
        'excluded' => 'Excluded',
    ],
    'reports' => [
        'statement_of_applicability' => 'Statement of Applicability',
        'risk_assessment' => 'Risk Assessment Report',
        'audit_report' => 'Audit Report',
        'remediation_plan' => 'Remediation Plan',
    ],
];