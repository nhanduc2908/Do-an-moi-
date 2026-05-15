<?php

namespace App\Http\Controllers\Api\V1\Module28_SystemAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AuditConfigService;

class AuditConfigController extends Controller
{
    protected $auditService;

    public function __construct(AuditConfigService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Get config
     */
    public function getConfig()
    {
        $config = $this->auditService->getConfig();

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Update config
     */
    public function updateConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audit_enabled' => 'nullable|boolean',
            'log_retention_days' => 'nullable|integer|min:30|max:3650',
            'excluded_ips' => 'nullable|array',
            'excluded_users' => 'nullable|array',
            'audit_events' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->auditService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Audit config updated'
        ]);
    }

    /**
     * Audit logs
     */
    public function auditLogs(Request $request)
    {
        $logs = $this->auditService->getAuditLogs([
            'user_id' => $request->user_id,
            'event' => $request->event,
            'severity' => $request->severity,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Audit detail
     */
    public function auditDetail($id)
    {
        $detail = $this->auditService->getAuditDetail($id);

        return response()->json([
            'success' => true,
            'data' => $detail
        ]);
    }

    /**
     * Retention policy
     */
    public function retentionPolicy()
    {
        $policy = $this->auditService->getRetentionPolicy();

        return response()->json([
            'success' => true,
            'data' => $policy
        ]);
    }

    /**
     * Update retention
     */
    public function updateRetention(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'retention_days' => 'required|integer|min:30|max:3650',
            'archive_before_delete' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $policy = $this->auditService->updateRetention($request->all());

        return response()->json([
            'success' => true,
            'data' => $policy,
            'message' => 'Retention policy updated'
        ]);
    }

    /**
     * Export audit logs
     */
    public function exportAuditLogs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => 'nullable|in:csv,json,pdf',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $export = $this->auditService->exportLogs($request->all());

        return response()->json([
            'success' => true,
            'data' => $export
        ]);
    }

    /**
     * Audit report
     */
    public function auditReport(Request $request)
    {
        $report = $this->auditService->generateReport([
            'period' => $request->period ?? 'month',
        ]);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}