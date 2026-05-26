<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Document Security Configuration
    |--------------------------------------------------------------------------
    */

    // Encryption settings
    'encryption' => [
        'algorithm' => env('DOC_ENCRYPTION_ALGO', 'aes-256-gcm'),
        'key_rotation_days' => 90,
        'enable_hmac' => true,
    ],

    // Access control hierarchy (lower level cannot access higher level)
    'access_levels' => [
        0 => 'Public',
        10 => 'Viewer',
        30 => 'Internal User',
        45 => 'Scanner',
        50 => 'Auditor',
        55 => 'Responder',
        60 => 'Analyst',
        70 => 'Compliance',
        75 => 'Risk Manager',
        80 => 'Security Manager',
        90 => 'Admin',
        100 => 'Super Admin',
    ],

    // Document classification levels
    'classifications' => [
        'public' => [
            'level' => 0,
            'color' => '#10b981',
            'requires_mfa' => false,
            'requires_justification' => false,
        ],
        'internal' => [
            'level' => 30,
            'color' => '#3b82f6',
            'requires_mfa' => false,
            'requires_justification' => false,
        ],
        'confidential' => [
            'level' => 60,
            'color' => '#f59e0b',
            'requires_mfa' => false,
            'requires_justification' => true,
        ],
        'restricted' => [
            'level' => 80,
            'color' => '#ef4444',
            'requires_mfa' => true,
            'requires_justification' => true,
        ],
        'top_secret' => [
            'level' => 100,
            'color' => '#000000',
            'requires_mfa' => true,
            'requires_justification' => true,
        ],
    ],

    // Audit settings
    'audit' => [
        'log_all_access' => true,
        'retention_days' => 365,
        'alert_on_denied_access' => true,
    ],

    // Watermark settings
    'watermark' => [
        'enabled' => true,
        'text' => 'CONFIDENTIAL',
        'opacity' => 0.3,
    ],
];