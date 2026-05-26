<?php

namespace App\Http\Controllers\RoleBased;

use App\Http\Controllers\Controller;
use App\Services\RoleBased\SuperAdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    protected $superAdminService;

    public function __construct(SuperAdminService $superAdminService)
    {
        $this->superAdminService = $superAdminService;
        $this->middleware(['auth', 'role:super_admin']);
    }

    /**
     * Dashboard chính của Super Admin
     */
    public function index()
    {
        $data = $this->superAdminService->getDashboardData();
        return view('admin.roles.super-admin.index', $data);
    }

    /**
     * System Monitor - Giám sát toàn bộ hệ thống
     */
    public function systemMonitor()
    {
        $data = $this->superAdminService->getSystemMetrics();
        return view('admin.roles.super-admin.system-monitor', $data);
    }

    /**
     * Audit Trail - Xem toàn bộ lịch sử hoạt động
     */
    public function auditTrail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_type' => 'nullable|string',
            'severity' => 'nullable|string|in:critical,high,medium,low,info',
            'user_id' => 'nullable|uuid|exists:users,id',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $this->superAdminService->getAuditTrail($request);
        return view('admin.roles.super-admin.audit-trail', $data);
    }

    /**
     * Master Control - Điều khiển hệ thống trung tâm
     */
    public function masterControl()
    {
        $data = $this->superAdminService->getMasterControlData();
        return view('admin.roles.super-admin.master-control', $data);
    }

    /**
     * API: Toggle system module
     */
    public function toggleModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module' => 'required|string|in:iam,encryption,assessment,ai,report,sync,notification,backup',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->superAdminService->toggleModule($request->module, $request->status);
        return response()->json($result);
    }

    /**
     * API: Clear all system cache
     */
    public function clearCache()
    {
        $result = $this->superAdminService->clearAllCache();
        return response()->json($result);
    }

    /**
     * API: Run system maintenance
     */
    public function runMaintenance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:enable,disable',
            'message' => 'nullable|string|max:255',
            'retry_minutes' => 'nullable|integer|min:5|max:1440',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->superAdminService->runMaintenance($request->all());
        return response()->json($result);
    }

    /**
     * API: Get system health status
     */
    public function systemHealth()
    {
        $health = $this->superAdminService->getSystemHealth();
        return response()->json($health);
    }

    /**
     * API: Get database status
     */
    public function databaseStatus()
    {
        $status = $this->superAdminService->getDatabaseStatus();
        return response()->json($status);
    }

    /**
     * API: Get queue status
     */
    public function queueStatus()
    {
        $status = $this->superAdminService->getQueueStatus();
        return response()->json($status);
    }

    /**
     * API: Get server info
     */
    public function serverInfo()
    {
        $info = $this->superAdminService->getServerInfo();
        return response()->json($info);
    }

    /**
     * API: Get all active sessions
     */
    public function activeSessions()
    {
        $sessions = $this->superAdminService->getActiveSessions();
        return response()->json($sessions);
    }

    /**
     * API: Terminate a user session
     */
    public function terminateSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->superAdminService->terminateSession($request->session_id);
        return response()->json($result);
    }

    /**
     * API: Get backup list
     */
    public function backupList()
    {
        $backups = $this->superAdminService->getBackupList();
        return response()->json($backups);
    }

    /**
     * API: Run manual backup
     */
    public function runBackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'nullable|string|in:full,database,files',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->superAdminService->runBackup($request->type ?? 'full');
        return response()->json($result);
    }

    /**
     * API: Get environment configuration
     */
    public function environmentConfig()
    {
        $config = $this->superAdminService->getEnvironmentConfig();
        return response()->json($config);
    }

    /**
     * API: Update environment variable
     */
    public function updateEnv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string',
            'value' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->superAdminService->updateEnvironmentVariable($request->key, $request->value);
        return response()->json($result);
    }

    /**
     * API: Get system logs
     */
    public function systemLogs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'nullable|string|in:laravel,security,audit,api,sync,ai',
            'lines' => 'nullable|integer|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $logs = $this->superAdminService->getSystemLogs(
            $request->channel ?? 'laravel',
            $request->lines ?? 100
        );
        return response()->json($logs);
    }

    /**
     * API: Get failed jobs
     */
    public function failedJobs()
    {
        $jobs = $this->superAdminService->getFailedJobs();
        return response()->json($jobs);
    }

    /**
     * API: Retry failed job
     */
    public function retryFailedJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->superAdminService->retryFailedJob($request->job_id);
        return response()->json($result);
    }

    /**
     * API: Delete all failed jobs
     */
    public function flushFailedJobs()
    {
        $result = $this->superAdminService->flushFailedJobs();
        return response()->json($result);
    }

    /**
     * API: Get scheduled tasks
     */
    public function scheduledTasks()
    {
        $tasks = $this->superAdminService->getScheduledTasks();
        return response()->json($tasks);
    }

    /**
     * API: Run scheduled task manually
     */
    public function runScheduledTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'command' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->superAdminService->runScheduledTask($request->command);
        return response()->json($result);
    }

    /**
     * API: Get security statistics
     */
    public function securityStats()
    {
        $stats = $this->superAdminService->getSecurityStatistics();
        return response()->json($stats);
    }

    /**
     * API: Export audit logs
     */
    public function exportAuditLogs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => 'required|string|in:csv,json,pdf',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
            'event_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        return $this->superAdminService->exportAuditLogs($request->all());
    }
}