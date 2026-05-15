<?php

return [
    'score_levels' => [
        5 => 'Excellent - Fully implemented and effective',
        4 => 'Good - Mostly implemented with minor gaps',
        3 => 'Satisfactory - Partially implemented',
        2 => 'Needs Improvement - Significant gaps',
        1 => 'Unsatisfactory - Not implemented',
        0 => 'Not Applicable',
    ],
    'scoring_methods' => [
        'manual' => 'Manual Assessment',
        'automated' => 'Automated Scan',
        'hybrid' => 'Hybrid (Manual + Automated)',
        'evidence_based' => 'Evidence-Based',
    ],
    'evidence_types' => [
        'document' => 'Documentation',
        'screenshot' => 'Screenshot',
        'log' => 'Log File',
        'scan_result' => 'Scan Result',
        'policy' => 'Policy Document',
        'certificate' => 'Certificate',
        'interview' => 'Interview Notes',
    ],
];