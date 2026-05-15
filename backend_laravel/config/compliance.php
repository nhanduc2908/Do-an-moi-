<?php

return [
    'standards' => [
        'iso27001' => ['name' => 'ISO/IEC 27001:2022', 'version' => '2022', 'enabled' => true, 'weight' => 1],
        'gdpr' => ['name' => 'General Data Protection Regulation', 'version' => '2016/679', 'enabled' => true, 'weight' => 1],
        'pci_dss' => ['name' => 'Payment Card Industry Data Security Standard', 'version' => '4.0', 'enabled' => true, 'weight' => 1],
        'hipaa' => ['name' => 'Health Insurance Portability and Accountability Act', 'version' => '2013', 'enabled' => false, 'weight' => 1],
        'soc2' => ['name' => 'Service Organization Control 2', 'version' => '2017', 'enabled' => false, 'weight' => 1],
        'nist_csf' => ['name' => 'NIST Cybersecurity Framework', 'version' => '1.1', 'enabled' => true, 'weight' => 1],
    ],
    'audit_retention_days' => 2555,
    'evidence_retention_days' => 2555,
    'auto_review_days' => 90,
];