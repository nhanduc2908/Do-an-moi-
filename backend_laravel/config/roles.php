<?php

return [
    'super_admin' => ['level' => 100, 'permissions' => ['*']],
    'admin' => ['level' => 80, 'permissions' => ['user.manage', 'role.manage', 'system.config', 'report.view', 'audit.view']],
    'security_manager' => ['level' => 70, 'permissions' => ['assessment.create', 'assessment.review', 'report.generate', 'incident.manage']],
    'security_analyst' => ['level' => 50, 'permissions' => ['assessment.create', 'vulnerability.scan', 'incident.view']],
    'compliance_officer' => ['level' => 60, 'permissions' => ['compliance.check', 'report.view', 'audit.view']],
    'auditor' => ['level' => 40, 'permissions' => ['audit.view', 'report.view']],
    'viewer' => ['level' => 10, 'permissions' => ['dashboard.view']],
];