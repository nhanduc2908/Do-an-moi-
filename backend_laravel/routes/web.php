<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    AuthController,
    DashboardController,
    AssessmentController,
    ReportController,
    UserController,
    RoleController,
    DomainController,
    CriteriaController,
    KeyController,
    IncidentController,
    VulnerabilityController,
    ComplianceController,
    AIController,
    SyncController,
    LogController,
    SettingController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// MFA Routes
Route::get('/mfa/verify', [AuthController::class, 'showMfaForm'])->name('mfa.verify');
Route::post('/mfa/verify', [AuthController::class, 'verifyMfaCode']);

// Authenticated Routes
Route::middleware(['auth', 'mfa'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Admin Routes
    Route::prefix('/admin')->name('admin.')->middleware(['role:admin|super_admin'])->group(function () {
        
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Security Score
        Route::prefix('/security-score')->name('security-score.')->group(function () {
            Route::get('/', [DashboardController::class, 'securityScore'])->name('index');
            Route::get('/details', [DashboardController::class, 'scoreDetails'])->name('details');
        });
        
        // Roles Management
        Route::resource('/roles', RoleController::class);
        Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
        Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
        
        // Users Management
        Route::resource('/users', UserController::class);
        Route::get('/users/{user}/view', [UserController::class, 'view'])->name('users.view');
        Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
        Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        
        // Domains Management
        Route::resource('/domains', DomainController::class);
        
        // Criteria Management
        Route::resource('/criteria', CriteriaController::class);
        Route::get('/criteria/import', [CriteriaController::class, 'importForm'])->name('criteria.import');
        Route::post('/criteria/import', [CriteriaController::class, 'import'])->name('criteria.import.process');
        Route::get('/criteria/ai-suggestions', [CriteriaController::class, 'aiSuggestions'])->name('criteria.ai-suggestions');
        Route::post('/criteria/ai-generate', [CriteriaController::class, 'aiGenerate'])->name('criteria.ai-generate');
        Route::post('/criteria/ai-suggestions/{id}/apply', [CriteriaController::class, 'applySuggestion'])->name('criteria.ai-apply');
        
        // Encryption Keys
        Route::resource('/keys', KeyController::class);
        Route::get('/keys/generate', [KeyController::class, 'generateForm'])->name('keys.generate');
        Route::post('/keys/generate', [KeyController::class, 'store'])->name('keys.store');
        Route::post('/keys/{key}/revoke', [KeyController::class, 'revoke'])->name('keys.revoke');
        Route::get('/keys/{key}/logs', [KeyController::class, 'logs'])->name('keys.logs');
        Route::get('/keys/{key}/details', [KeyController::class, 'details'])->name('keys.details');
        
        // Assessments
        Route::resource('/assessments', AssessmentController::class);
        Route::get('/assessments/{assessment}/view', [AssessmentController::class, 'view'])->name('assessments.view');
        Route::get('/assessments/{assessment}/continue', [AssessmentController::class, 'continue'])->name('assessments.continue');
        Route::post('/assessments/{assessment}/review', [AssessmentController::class, 'review'])->name('assessments.review');
        Route::post('/assessments/{assessment}/review-submit', [AssessmentController::class, 'submitReview'])->name('assessments.review.submit');
        Route::get('/assessments/{assessment}/export', [AssessmentController::class, 'export'])->name('assessments.export');
        
        // Incidents
        Route::resource('/incidents', IncidentController::class);
        Route::get('/incidents/{incident}/resolve', [IncidentController::class, 'resolveForm'])->name('incidents.resolve');
        Route::post('/incidents/{incident}/resolve', [IncidentController::class, 'resolve'])->name('incidents.resolve.submit');
        Route::post('/incidents/{incident}/comment', [IncidentController::class, 'addComment'])->name('incidents.comment');
        
        // Vulnerabilities
        Route::resource('/vulnerabilities', VulnerabilityController::class);
        Route::get('/vulnerabilities/{vulnerability}/remediate', [VulnerabilityController::class, 'remediateForm'])->name('vulnerabilities.remediate');
        Route::post('/vulnerabilities/{vulnerability}/remediate', [VulnerabilityController::class, 'remediate'])->name('vulnerabilities.remediate.submit');
        Route::post('/vulnerabilities/scan', [VulnerabilityController::class, 'scan'])->name('vulnerabilities.scan');
        Route::get('/vulnerabilities/export', [VulnerabilityController::class, 'export'])->name('vulnerabilities.export');
        
        // Compliance
        Route::prefix('/compliance')->name('compliance.')->group(function () {
            Route::get('/', [ComplianceController::class, 'index'])->name('index');
            Route::get('/iso27001', [ComplianceController::class, 'iso27001'])->name('iso27001');
            Route::get('/gdpr', [ComplianceController::class, 'gdpr'])->name('gdpr');
            Route::get('/pci-dss', [ComplianceController::class, 'pciDss'])->name('pci-dss');
            Route::get('/audit', [ComplianceController::class, 'audit'])->name('audit');
            Route::post('/audit/run', [ComplianceController::class, 'runAudit'])->name('audit.run');
            Route::post('/upload-evidence', [ComplianceController::class, 'uploadEvidence'])->name('upload-evidence');
            Route::get('/export/{standard}', [ComplianceController::class, 'export'])->name('export');
            Route::get('/{standard}/show', [ComplianceController::class, 'show'])->name('show');
        });
        
        // Reports
        Route::resource('/reports', ReportController::class);
        Route::get('/reports/generate', [ReportController::class, 'generateForm'])->name('reports.generate');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.store');
        Route::get('/reports/schedule', [ReportController::class, 'schedule'])->name('reports.schedule');
        Route::post('/reports/schedule', [ReportController::class, 'storeSchedule'])->name('reports.schedule.store');
        Route::put('/reports/schedule/{schedule}', [ReportController::class, 'updateSchedule'])->name('reports.schedule.update');
        Route::delete('/reports/schedule/{schedule}', [ReportController::class, 'destroySchedule'])->name('reports.schedule.destroy');
        Route::post('/reports/schedule/{schedule}/toggle', [ReportController::class, 'toggleSchedule'])->name('reports.schedule.toggle');
        Route::post('/reports/share', [ReportController::class, 'share'])->name('reports.share');
        Route::get('/reports/{report}/preview', [ReportController::class, 'preview'])->name('reports.preview');
        
        // AI Engine
        Route::prefix('/ai')->name('ai.')->group(function () {
            Route::get('/dashboard', [AIController::class, 'dashboard'])->name('dashboard');
            Route::get('/threat-detection', [AIController::class, 'threatDetection'])->name('threat-detection');
            Route::get('/chat', [AIController::class, 'chat'])->name('chat');
            Route::post('/chat/send', [AIController::class, 'sendChat'])->name('chat.send');
            Route::get('/chat/history/{session}', [AIController::class, 'chatHistory'])->name('chat.history');
            Route::get('/generate-criteria', [AIController::class, 'generateCriteriaForm'])->name('generate-criteria');
            Route::post('/criteria/generate', [AIController::class, 'generateCriteria'])->name('criteria.generate');
            Route::post('/criteria/apply', [AIController::class, 'applyCriteria'])->name('criteria.apply');
            Route::get('/predictions', [AIController::class, 'predictions'])->name('predictions');
            Route::post('/scan/trigger', [AIController::class, 'triggerScan'])->name('scan.trigger');
            Route::get('/threats/stream', [AIController::class, 'threatStream'])->name('threats.stream');
        });
        
        // Sync
        Route::prefix('/sync')->name('sync.')->group(function () {
            Route::get('/status', [SyncController::class, 'status'])->name('status');
            Route::get('/logs', [SyncController::class, 'logs'])->name('logs');
            Route::get('/manual', [SyncController::class, 'manualSyncForm'])->name('manual');
            Route::post('/manual/execute', [SyncController::class, 'executeManualSync'])->name('manual.execute');
            Route::post('/{target}/trigger', [SyncController::class, 'triggerSync'])->name('trigger');
            Route::get('/logs/filter', [SyncController::class, 'filterLogs'])->name('logs.filter');
            Route::get('/logs/export', [SyncController::class, 'exportLogs'])->name('logs.export');
        });
        
        // Logs
        Route::prefix('/logs')->name('logs.')->group(function () {
            Route::get('/security', [LogController::class, 'securityLogs'])->name('security');
            Route::get('/security/filter', [LogController::class, 'filterSecurityLogs'])->name('security.filter');
            Route::get('/security/export', [LogController::class, 'exportSecurityLogs'])->name('security.export');
            Route::get('/audit', [LogController::class, 'auditLogs'])->name('audit');
            Route::get('/audit/{id}', [LogController::class, 'auditDetail'])->name('audit.detail');
            Route::get('/access', [LogController::class, 'accessLogs'])->name('access');
            Route::get('/api', [LogController::class, 'apiLogs'])->name('api');
            Route::get('/api/{id}', [LogController::class, 'apiDetail'])->name('api.detail');
        });
        
        // Settings
        Route::prefix('/settings')->name('settings.')->group(function () {
            Route::get('/general', [SettingController::class, 'general'])->name('general');
            Route::get('/security', [SettingController::class, 'security'])->name('security');
            Route::get('/notification', [SettingController::class, 'notification'])->name('notification');
            Route::get('/backup', [SettingController::class, 'backup'])->name('backup');
            Route::get('/api', [SettingController::class, 'api'])->name('api');
            
            Route::put('/update', [SettingController::class, 'update'])->name('update');
            Route::put('/security/update', [SettingController::class, 'updateSecurity'])->name('security.update');
            Route::put('/notification/update', [SettingController::class, 'updateNotification'])->name('notification.update');
            Route::put('/backup/update', [SettingController::class, 'updateBackup'])->name('backup.update');
            Route::put('/api/update', [SettingController::class, 'updateApi'])->name('api.update');
            
            Route::post('/notification/test', [SettingController::class, 'testNotification'])->name('notification.test');
            Route::post('/backup/run', [SettingController::class, 'runBackup'])->name('backup.run');
            Route::post('/api/generate-key', [SettingController::class, 'generateApiKey'])->name('api.generate-key');
            Route::delete('/api/revoke-key/{key}', [SettingController::class, 'revokeApiKey'])->name('api.revoke-key');
        });
        
        // Profile
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    });
    / Role-based dashboard routes
Route::middleware(['auth', RoleMiddleware::class])->group(function () {
    
    // Super Admin only
    Route::middleware(['role:super_admin'])->prefix('admin')->group(function () {
        Route::get('/super-dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'superAdmin'])
            ->name('admin.roles.super-admin');
    });
    
    // Admin only
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/admin-dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'admin'])
            ->name('admin.roles.admin');
    });
    
    // Security Manager
    Route::middleware(['role:security_manager'])->prefix('admin')->group(function () {
        Route::get('/security-dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'securityManager'])
            ->name('admin.roles.security-manager');
    });
    
    // Compliance Officer
    Route::middleware(['role:compliance_officer'])->prefix('admin')->group(function () {
        Route::get('/compliance-dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'complianceOfficer'])
            ->name('admin.roles.compliance-officer');
    });
    
    // Risk Manager
    Route::middleware(['role:risk_manager'])->prefix('admin')->group(function () {
        Route::get('/risk-dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'riskManager'])
            ->name('admin.roles.risk-manager');
    });
    
    // Security Analyst
    Route::middleware(['role:security_analyst'])->prefix('admin')->group(function () {
        Route::get('/analyst-dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'securityAnalyst'])
            ->name('admin.roles.security-analyst');
    });
    
    // Incident Responder
    Route::middleware(['role:incident_responder'])->prefix('admin')->group(function () {
        Route::get('/incident-dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'incidentResponder'])
            ->name('admin.roles.incident-responder');
    });
    
    // Vulnerability Scanner
    Route::middleware(['role:vulnerability_scanner'])->prefix('admin')->group(function () {
        Route::get('/scanner-dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'vulnerabilityScanner'])
            ->name('admin.roles.vulnerability-scanner');
    });
    
    // Auditor
    Route::middleware(['role:auditor'])->prefix('admin')->group(function () {
        Route::get('/auditor-dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'auditor'])
            ->name('admin.roles.auditor');
    });
    
    // Viewer
    Route::middleware(['role:viewer'])->prefix('admin')->group(function () {
        Route::get('/viewer-dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'viewer'])
            ->name('admin.roles.viewer');
    });
});

// Redirect to role-specific dashboard after login
Route::get('/dashboard', [App\Http\Controllers\Web\AdminDashboardController::class, 'redirectToRoleDashboard'])
    ->middleware(['auth', 'mfa'])
    ->name('admin.dashboard');
    
    // ==================== DEMO ROUTES ====================
Route::get('/demo', [DemoLoginController::class, 'showDemoAccounts'])->name('demo.accounts');
Route::post('/demo/login', [DemoLoginController::class, 'demoLogin'])->name('demo.login');
Route::get('/demo/login/{role}', [DemoLoginController::class, 'quickLogin'])->name('demo.quick-login');
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});