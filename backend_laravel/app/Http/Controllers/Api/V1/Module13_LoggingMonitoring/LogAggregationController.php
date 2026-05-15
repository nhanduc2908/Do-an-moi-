<?php

namespace App\Http\Controllers\Api\V1\Module13_LoggingMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\LogAggregationService;

class LogAggregationController extends Controller
{
    protected $logService;

    public function __construct(LogAggregationService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Aggregated logs
     */
    public function aggregated(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_by' => 'required|in:hour,day,week,month',
            'source' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $aggregated = $this->logService->getAggregatedLogs($request->all());

        return response()->json([
            'success' => true,
            'data' => $aggregated
        ]);
    }

    /**
     * Log retention policy
     */
    public function retentionPolicy()
    {
        $policy = $this->logService->getRetentionPolicy();

        return response()->json([
            'success' => true,
            'data' => $policy
        ]);
    }

    /**
     * Cập nhật retention policy
     */
    public function updateRetention(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer|min=7|max=3650',
            'archive_before_delete' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $policy = $this->logService->updateRetentionPolicy($request->all());

        return response()->json([
            'success' => true,
            'data' => $policy,
            'message' => 'Cập nhật retention policy thành công'
        ]);
    }

    /**
     * Archive logs
     */
    public function archive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->logService->archiveLogs(
            $request->start_date,
            $request->end_date
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Archive logs thành công'
        ]);
    }

    /**
     * Restore logs
     */
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archive_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->logService->restoreLogs($request->archive_id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Restore logs thành công' : 'Restore thất bại'
        ]);
    }

    /**
     * Log storage stats
     */
    public function storageStats()
    {
        $stats = $this->logService->getStorageStats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}