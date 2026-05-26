<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleBased\{
    SuperAdminController,
    AdminController,
    SecurityManagerController,
    ComplianceOfficerController,
    RiskManagerController,
    SecurityAnalystController,
    IncidentResponderController,
    VulnerabilityScannerController,
    AuditorController,
    ViewerController
};

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::get('/system-monitor', [SuperAdminController::class, 'systemMonitor'])->name('system-monitor');
    Route::get('/audit-trail', [SuperAdminController::class, 'auditTrail'])->name('audit-trail');
    Route::get('/master-control', [SuperAdminController::class, 'masterControl'])->name('master-control');
    
    Route::post('/toggle-module', [SuperAdminController::class, 'toggleModule'])->name('toggle-module');
    Route::post('/clear-cache', [SuperAdminController::class, 'clearCache'])->name('clear-cache');
    Route::post('/run-maintenance', [SuperAdminController::class, 'runMaintenance'])->name('run-maintenance');
    Route::get('/system-health', [SuperAdminController::class, 'systemHealth'])->name('system-health');
    Route::get('/database-status', [SuperAdminController::class, 'databaseStatus'])->name('database-status');
    Route::get('/queue-status', [SuperAdminController::class, 'queueStatus'])->name('queue-status');
    Route::get('/server-info', [SuperAdminController::class, 'serverInfo'])->name('server-info');
    Route::get('/active-sessions', [SuperAdminController::class, 'activeSessions'])->name('active-sessions');
    Route::delete('/terminate-session', [SuperAdminController::class, 'terminateSession'])->name('terminate-session');
    Route::get('/backups', [SuperAdminController::class, 'backupList'])->name('backups');
    Route::post('/run-backup', [SuperAdminController::class, 'runBackup'])->name('run-backup');
    Route::get('/env-config', [SuperAdminController::class, 'environmentConfig'])->name('env-config');
    Route::get('/system-logs', [SuperAdminController::class, 'systemLogs'])->name('system-logs');
    Route::get('/failed-jobs', [SuperAdminController::class, 'failedJobs'])->name('failed-jobs');
    Route::post('/retry-job', [SuperAdminController::class, 'retryFailedJob'])->name('retry-job');
    Route::delete('/flush-jobs', [SuperAdminController::class, 'flushFailedJobs'])->name('flush-jobs');
    Route::get('/scheduled-tasks', [SuperAdminController::class, 'scheduledTasks'])->name('scheduled-tasks');
    Route::get('/security-stats', [SuperAdminController::class, 'securityStats'])->name('security-stats');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'userManagement'])->name('users');
    Route::post('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/users/{id}', [AdminController::class, 'getUser'])->name('users.get');
    Route::get('/roles', [AdminController::class, 'roleManagement'])->name('roles');
    Route::post('/roles/create', [AdminController::class, 'createRole'])->name('roles.create');
    Route::put('/roles/{id}', [AdminController::class, 'updateRole'])->name('roles.update');
    Route::delete('/roles/{id}', [AdminController::class, 'deleteRole'])->name('roles.delete');
    Route::post('/roles/assign-permissions', [AdminController::class, 'assignPermissions'])->name('roles.assign-permissions');
    Route::get('/roles/{id}/permissions', [AdminController::class, 'getRolePermissions'])->name('roles.permissions');
    Route::get('/permissions/all', [AdminController::class, 'getAllPermissions'])->name('permissions.all');
    Route::get('/system-config', [AdminController::class, 'systemConfig'])->name('system-config');
    Route::put('/system-config', [AdminController::class, 'updateConfig'])->name('system-config.update');
});

// Security Manager Routes
Route::middleware(['auth', 'role:security_manager'])->prefix('security-manager')->name('security-manager.')->group(function () {
    Route::get('/dashboard', [SecurityManagerController::class, 'index'])->name('dashboard');
    Route::get('/incidents', [SecurityManagerController::class, 'incidentResponse'])->name('incidents');
    Route::get('/team', [SecurityManagerController::class, 'teamManagement'])->name('team');
    Route::get('/policies', [SecurityManagerController::class, 'securityPolicies'])->name('policies');
    Route::post('/policies/approve', [SecurityManagerController::class, 'approvePolicy'])->name('policies.approve');
    Route::post('/incidents/assign', [SecurityManagerController::class, 'assignIncident'])->name('incidents.assign');
    Route::post('/incidents/escalate', [SecurityManagerController::class, 'escalateIncident'])->name('incidents.escalate');
    Route::get('/incidents/stats', [SecurityManagerController::class, 'getIncidentStats'])->name('incidents.stats');
    Route::get('/team/performance', [SecurityManagerController::class, 'getTeamPerformance'])->name('team.performance');
    Route::post('/policies/create', [SecurityManagerController::class, 'createPolicy'])->name('policies.create');
});

// Compliance Officer Routes
Route::middleware(['auth', 'role:compliance_officer'])->prefix('compliance-officer')->name('compliance-officer.')->group(function () {
    Route::get('/dashboard', [ComplianceOfficerController::class, 'index'])->name('dashboard');
    Route::get('/audit-schedule', [ComplianceOfficerController::class, 'auditSchedule'])->name('audit-schedule');
    Route::get('/evidence', [ComplianceOfficerController::class, 'evidenceCollection'])->name('evidence');
    Route::get('/reports', [ComplianceOfficerController::class, 'complianceReports'])->name('reports');
    Route::post('/evidence/upload', [ComplianceOfficerController::class, 'uploadEvidence'])->name('evidence.upload');
    Route::post('/audit/run', [ComplianceOfficerController::class, 'runAudit'])->name('audit.run');
    Route::get('/status/{standard}', [ComplianceOfficerController::class, 'getComplianceStatus'])->name('status');
    Route::post('/generate-report', [ComplianceOfficerController::class, 'generateReport'])->name('generate-report');
});

// Risk Manager Routes
Route::middleware(['auth', 'role:risk_manager'])->prefix('risk-manager')->name('risk-manager.')->group(function () {
    Route::get('/dashboard', [RiskManagerController::class, 'index'])->name('dashboard');
    Route::get('/register', [RiskManagerController::class, 'riskRegister'])->name('register');
    Route::get('/assessment', [RiskManagerController::class, 'riskAssessment'])->name('assessment');
    Route::get('/treatment', [RiskManagerController::class, 'riskTreatment'])->name('treatment');
    Route::post('/risks/create', [RiskManagerController::class, 'createRisk'])->name('risks.create');
    Route::put('/risks/{id}', [RiskManagerController::class, 'updateRisk'])->name('risks.update');
    Route::delete('/risks/{id}', [RiskManagerController::class, 'deleteRisk'])->name('risks.delete');
    Route::post('/calculate-score', [RiskManagerController::class, 'calculateRiskScore'])->name('calculate-score');
    Route::get('/matrix', [RiskManagerController::class, 'getRiskMatrix'])->name('matrix');
    Route::post('/generate-report', [RiskManagerController::class, 'generateRiskReport'])->name('generate-report');
});

// Security Analyst Routes
Route::middleware(['auth', 'role:security_analyst'])->prefix('security-analyst')->name('security-analyst.')->group(function () {
    Route::get('/dashboard', [SecurityAnalystController::class, 'index'])->name('dashboard');
    Route::get('/threat-hunting', [SecurityAnalystController::class, 'threatHunting'])->name('threat-hunting');
    Route::get('/siem', [SecurityAnalystController::class, 'siemDashboard'])->name('siem');
    Route::get('/iocs', [SecurityAnalystController::class, 'iocManagement'])->name('iocs');
    Route::get('/iocs/search', [SecurityAnalystController::class, 'searchIocs'])->name('iocs.search');
    Route::post('/iocs/add', [SecurityAnalystController::class, 'addIoc'])->name('iocs.add');
    Route::get('/threat-intel', [SecurityAnalystController::class, 'getThreatIntel'])->name('threat-intel');
    Route::post('/siem/query', [SecurityAnalystController::class, 'runQuery'])->name('siem.query');
});

// Incident Responder Routes
Route::middleware(['auth', 'role:incident_responder'])->prefix('incident-responder')->name('incident-responder.')->group(function () {
    Route::get('/dashboard', [IncidentResponderController::class, 'index'])->name('dashboard');
    Route::get('/runbooks', [IncidentResponderController::class, 'runbookExecutor'])->name('runbooks');
    Route::get('/forensics', [IncidentResponderController::class, 'forensicTools'])->name('forensics');
    Route::get('/containment', [IncidentResponderController::class, 'containmentActions'])->name('containment');
    Route::post('/runbooks/execute', [IncidentResponderController::class, 'executeRunbook'])->name('runbooks.execute');
    Route::post('/systems/isolate', [IncidentResponderController::class, 'isolateSystem'])->name('systems.isolate');
    Route::post('/ip/block', [IncidentResponderController::class, 'blockIp'])->name('ip.block');
    Route::post('/forensics/collect', [IncidentResponderController::class, 'collectForensics'])->name('forensics.collect');
});

// Vulnerability Scanner Routes
Route::middleware(['auth', 'role:vulnerability_scanner'])->prefix('vulnerability-scanner')->name('vulnerability-scanner.')->group(function () {
    Route::get('/dashboard', [VulnerabilityScannerController::class, 'index'])->name('dashboard');
    Route::get('/schedule', [VulnerabilityScannerController::class, 'scanScheduler'])->name('schedule');
    Route::get('/database', [VulnerabilityScannerController::class, 'vulnerabilityDb'])->name('database');
    Route::get('/patch-validator', [VulnerabilityScannerController::class, 'patchValidator'])->name('patch-validator');
    Route::post('/scan/start', [VulnerabilityScannerController::class, 'startScan'])->name('scan.start');
    Route::get('/scan/{scanId}/status', [VulnerabilityScannerController::class, 'getScanStatus'])->name('scan.status');
    Route::get('/scan/{scanId}/results', [VulnerabilityScannerController::class, 'getScanResults'])->name('scan.results');
    Route::post('/scan/schedule', [VulnerabilityScannerController::class, 'scheduleScan'])->name('scan.schedule');
    Route::get('/cve/{cveId}', [VulnerabilityScannerController::class, 'getCveDetails'])->name('cve.details');
    Route::post('/patch/validate', [VulnerabilityScannerController::class, 'validatePatch'])->name('patch.validate');
});

// Auditor Routes
Route::middleware(['auth', 'role:auditor'])->prefix('auditor')->name('auditor.')->group(function () {
    Route::get('/dashboard', [AuditorController::class, 'index'])->name('dashboard');
    Route::get('/logs', [AuditorController::class, 'logReviewer'])->name('logs');
    Route::get('/access-reports', [AuditorController::class, 'accessReports'])->name('access-reports');
    Route::get('/compliance-checker', [AuditorController::class, 'complianceChecker'])->name('compliance-checker');
    Route::post('/logs/export', [AuditorController::class, 'exportLogs'])->name('logs.export');
    Route::get('/audit-trail', [AuditorController::class, 'getAuditTrail'])->name('audit-trail');
    Route::get('/user/{userId}/access', [AuditorController::class, 'getUserAccessReport'])->name('user.access');
    Route::post('/compliance/run', [AuditorController::class, 'runComplianceCheck'])->name('compliance.run');
});

// Viewer Routes
Route::middleware(['auth', 'role:viewer'])->prefix('viewer')->name('viewer.')->group(function () {
    Route::get('/dashboard', [ViewerController::class, 'index'])->name('dashboard');
    Route::get('/security-dashboard', [ViewerController::class, 'securityDashboard'])->name('security-dashboard');
    Route::get('/reports', [ViewerController::class, 'reportViewer'])->name('reports');
    Route::get('/report/{reportId}', [ViewerController::class, 'viewReport'])->name('report.view');
    Route::get('/security-score', [ViewerController::class, 'getSecurityScore'])->name('security-score');
    Route::get('/widgets', [ViewerController::class, 'getDashboardWidgets'])->name('widgets');
});

// Redirect to role-specific dashboard
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    $role = $user->roles->first();
    
    $routes = [
        'super_admin' => 'super-admin.dashboard',
        'admin' => 'admin.dashboard',
        'security_manager' => 'security-manager.dashboard',
        'compliance_officer' => 'compliance-officer.dashboard',
        'risk_manager' => 'risk-manager.dashboard',
        'security_analyst' => 'security-analyst.dashboard',
        'incident_responder' => 'incident-responder.dashboard',
        'vulnerability_scanner' => 'vulnerability-scanner.dashboard',
        'auditor' => 'auditor.dashboard',
        'viewer' => 'viewer.dashboard',
    ];
    
    $route = $routes[$role->name] ?? 'viewer.dashboard';
    return redirect()->route($route);
})->name('dashboard.redirect');