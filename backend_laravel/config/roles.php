<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 10 Role Definitions
    |--------------------------------------------------------------------------
    */

    'super_admin' => [
        'level' => 100,
        'display_name' => 'Super Administrator',
        'description' => 'Full system access with all permissions',
        'color' => '#dc3545',
        'icon' => '👑',
        'dashboard_route' => 'admin.roles.super-admin',
        'permissions' => ['*'],
    ],

    'admin' => [
        'level' => 90,
        'display_name' => 'Administrator',
        'description' => 'System administration and configuration',
        'color' => '#e74c3c',
        'icon' => '⚙️',
        'dashboard_route' => 'admin.roles.admin',
        'permissions' => ['user.*', 'role.*', 'system.*', 'audit.*'],
    ],

    'security_manager' => [
        'level' => 80,
        'display_name' => 'Security Manager',
        'description' => 'Manage security operations and team',
        'color' => '#e67e22',
        'icon' => '🛡️',
        'dashboard_route' => 'admin.roles.security-manager',
        'permissions' => ['assessment.*', 'incident.*', 'vulnerability.*', 'team.*'],
    ],

    'compliance_officer' => [
        'level' => 70,
        'display_name' => 'Compliance Officer',
        'description' => 'Manage compliance and audit activities',
        'color' => '#2ecc71',
        'icon' => '📋',
        'dashboard_route' => 'admin.roles.compliance-officer',
        'permissions' => ['compliance.*', 'audit.*', 'evidence.*'],
    ],

    'risk_manager' => [
        'level' => 75,
        'display_name' => 'Risk Manager',
        'description' => 'Manage risk assessment and mitigation',
        'color' => '#f39c12',
        'icon' => '📊',
        'dashboard_route' => 'admin.roles.risk-manager',
        'permissions' => ['risk.*', 'assessment.*', 'vulnerability.view'],
    ],

    'security_analyst' => [
        'level' => 60,
        'display_name' => 'Security Analyst',
        'description' => 'Analyze security events and incidents',
        'color' => '#3498db',
        'icon' => '🔍',
        'dashboard_route' => 'admin.roles.security-analyst',
        'permissions' => ['incident.*', 'vulnerability.*', 'threat.view'],
    ],

    'incident_responder' => [
        'level' => 55,
        'display_name' => 'Incident Responder',
        'description' => 'Handle security incidents',
        'color' => '#e84393',
        'icon' => '🚨',
        'dashboard_route' => 'admin.roles.incident-responder',
        'permissions' => ['incident.*', 'forensic.view', 'recovery.*'],
    ],

    'vulnerability_scanner' => [
        'level' => 45,
        'display_name' => 'Vulnerability Scanner',
        'description' => 'Scan and identify vulnerabilities',
        'color' => '#1abc9c',
        'icon' => '🔬',
        'dashboard_route' => 'admin.roles.vulnerability-scanner',
        'permissions' => ['vulnerability.scan', 'vulnerability.view'],
    ],

    'auditor' => [
        'level' => 30,
        'display_name' => 'Auditor',
        'description' => 'View audit logs and reports',
        'color' => '#95a5a6',
        'icon' => '📜',
        'dashboard_route' => 'admin.roles.auditor',
        'permissions' => ['audit.view', 'report.view'],
    ],

    'viewer' => [
        'level' => 10,
        'display_name' => 'Viewer',
        'description' => 'Read-only access to dashboards',
        'color' => '#7f8c8d',
        'icon' => '👁️',
        'dashboard_route' => 'admin.roles.viewer',
        'permissions' => ['dashboard.view'],
    ],
];