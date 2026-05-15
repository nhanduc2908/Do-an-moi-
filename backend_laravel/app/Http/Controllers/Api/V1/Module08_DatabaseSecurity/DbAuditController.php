<?php

namespace App\Http\Controllers\Api\V1\Module08_DatabaseSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatabaseAuditLog;
use App\Services\DatabaseAuditService;

class DbAuditController extends Controller
{
    protected $auditService;

    public function __construct(DatabaseAuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Danh sách audit logs
     */
    public function index(Request $request)
    {
        $logs = DatabaseAuditLog::with('user')
            ->when($request->table, function($query, $table) {
                $query->where('table_name', $table);
            })
            ->when($request->action, function($query, $action) {
                $query->where('action', $action);
            })
            ->when($request->user_id, function($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 50);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Chi tiết audit log
     */
    public function show($id)
    {
        $log = DatabaseAuditLog::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $log
        ]);
    }

    /**
     * Thống kê audit
     */
    public function statistics(Request $request)
    {
        $stats = $this->auditService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Cấu hình audit
     */
    public function config(Request $request)
    {
        $config = $this->auditService->getConfig();

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Cập nhật cấu hình audit
     */
    public function updateConfig(Request $request)
    {
        $config = $this->auditService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình audit thành công'
        ]);
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $format = $request->format ?? 'csv';
        $logs = $this->auditService->exportLogs($request->all(), $format);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}