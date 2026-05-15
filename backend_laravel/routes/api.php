<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    AssessmentController,
    ReportController,
    KeyController,
    ScanController,
    IncidentController,
    VulnerabilityController,
    ComplianceController,
    AIController,
    SyncController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes (No authentication required)
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/auth/verify-mfa', [AuthController::class, 'verifyMfa']);

// Authenticated API Routes
Route::middleware(['auth:api'])->group(function () {
    
    // User Management
    Route::prefix('/user')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/mfa/enable', [AuthController::class, 'enableMfa']);
        Route::post('/mfa/disable', [AuthController::class, 'disableMfa']);
        Route::get('/mfa/qrcode', [AuthController::class, 'getMfaQrCode']);
    });
    
    // Assessment Routes
    Route::apiResource('assessments', AssessmentController::class);
    Route::post('/assessments/{assessment}/submit', [AssessmentController::class, 'submit']);
    Route::post('/assessments/{assessment}/review', [AssessmentController::class, 'review']);
    Route::get('/assessments/{assessment}/progress', [AssessmentController::class, 'progress']);
    Route::get('/assessments/{assessment}/export', [AssessmentController::class, 'export']);
    
    // Report Routes
    Route::apiResource('reports', ReportController::class);
    Route::get('/reports/{report}/download', [ReportController::class, 'download']);
    Route::post('/reports/{report}/share', [ReportController::class, 'share']);
    Route::post('/reports/schedule', [ReportController::class, 'schedule']);
    
    // Encryption Key Routes
    Route::apiResource('keys', KeyController::class);
    Route::post('/keys/{key}/revoke', [KeyController::class, 'revoke']);
    Route::post('/keys/{key}/rotate', [KeyController::class, 'rotate']);
    Route::post('/keys/verify', [KeyController::class, 'verify']);
    
    // Security Scan Routes
    Route::prefix('/scans')->group(function () {
        Route::post('/web', [ScanController::class, 'webScan']);
        Route::post('/network', [ScanController::class, 'networkScan']);
        Route::post('/vulnerability', [ScanController::class, 'vulnerabilityScan']);
        Route::get('/status/{scanId}', [ScanController::class, 'scanStatus']);
        Route::get('/results/{scanId}', [ScanController::class, 'scanResults']);
    });
    
    // Incident Routes
    Route::apiResource('incidents', IncidentController::class);
    Route::post('/incidents/{incident}/resolve', [IncidentController::class, 'resolve']);
    Route::post('/incidents/{incident}/comment', [IncidentController::class, 'addComment']);
    Route::get('/incidents/stats/summary', [IncidentController::class, 'summary']);
    
    // Vulnerability Routes
    Route::apiResource('vulnerabilities', VulnerabilityController::class);
    Route::post('/vulnerabilities/{vulnerability}/remediate', [VulnerabilityController::class, 'remediate']);
    Route::get('/vulnerabilities/cve/{cveId}', [VulnerabilityController::class, 'showByCve']);
    
    // Compliance Routes
    Route::prefix('/compliance')->group(function () {
        Route::get('/iso27001', [ComplianceController::class, 'iso27001']);
        Route::get('/gdpr', [ComplianceController::class, 'gdpr']);
        Route::get('/pci-dss', [ComplianceController::class, 'pciDss']);
        Route::post('/audit/run', [ComplianceController::class, 'runAudit']);
        Route::get('/audit/results', [ComplianceController::class, 'auditResults']);
        Route::post('/evidence/upload', [ComplianceController::class, 'uploadEvidence']);
    });
    
    // AI Engine Routes
    Route::prefix('/ai')->group(function () {
        Route::post('/detect', [AIController::class, 'detectThreat']);
        Route::post('/anomaly', [AIController::class, 'detectAnomaly']);
        Route::post('/chat', [AIController::class, 'chat']);
        Route::post('/generate-criteria', [AIController::class, 'generateCriteria']);
        Route::get('/predictions', [AIController::class, 'predictions']);
        Route::get('/dashboard', [AIController::class, 'dashboard']);
    });
    
    // Sync Routes
    Route::prefix('/sync')->group(function () {
        Route::post('/flutter', [SyncController::class, 'syncToFlutter']);
        Route::post('/firebase', [SyncController::class, 'syncToFirebase']);
        Route::get('/status', [SyncController::class, 'syncStatus']);
        Route::get('/logs', [SyncController::class, 'syncLogs']);
    });
    
    // Dashboard Routes
    Route::prefix('/dashboard')->group(function () {
        Route::get('/stats', [DashboardController::class, 'stats']);
        Route::get('/security-score', [DashboardController::class, 'securityScore']);
        Route::get('/recent-activities', [DashboardController::class, 'recentActivities']);
        Route::get('/widgets', [DashboardController::class, 'widgets']);
    });
    
    // Admin Routes (Role based)
    Route::middleware(['role:admin|super_admin'])->prefix('/admin')->group(function () {
        
        // User Management
        Route::apiResource('users', UserManagementController::class);
        Route::post('/users/{user}/suspend', [UserManagementController::class, 'suspend']);
        Route::post('/users/{user}/activate', [UserManagementController::class, 'activate']);
        
        // Role Management
        Route::apiResource('roles', RoleManagementController::class);
        Route::put('/roles/{role}/permissions', [RoleManagementController::class, 'updatePermissions']);
        
        // Domain & Criteria Management
        Route::apiResource('domains', DomainController::class);
        Route::apiResource('criteria', CriteriaController::class);
        Route::post('/criteria/import', [CriteriaController::class, 'import']);
        Route::post('/criteria/ai-generate', [CriteriaController::class, 'aiGenerate']);
        
        // System Settings
        Route::get('/settings', [SystemController::class, 'getSettings']);
        Route::put('/settings', [SystemController::class, 'updateSettings']);
        Route::get('/logs', [SystemController::class, 'getLogs']);
        Route::get('/audit-logs', [SystemController::class, 'getAuditLogs']);
        
        // Backup Management
        Route::post('/backup/run', [BackupController::class, 'runBackup']);
        Route::get('/backup/list', [BackupController::class, 'listBackups']);
        Route::post('/backup/restore', [BackupController::class, 'restoreBackup']);
    });
});

// WebSocket Broadcasting
Route::middleware(['auth:api'])->group(function () {
    Route::post('/broadcasting/auth', [BroadcastController::class, 'authenticate']);
});