<?php

namespace App\Http\Controllers\Api\V1\Module08_DatabaseSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SqlMonitorService;

class SqlMonitorController extends Controller
{
    protected $sqlMonitorService;

    public function __construct(SqlMonitorService $sqlMonitorService)
    {
        $this->sqlMonitorService = $sqlMonitorService;
    }

    /**
     * Theo dõi SQL queries
     */
    public function monitor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'duration' => 'nullable|integer|min=1|max=300',
            'slow_query_threshold' => 'nullable|integer|min=100|max=10000',
        ]);

        $result = $this->sqlMonitorService->startMonitoring([
            'duration' => $request->duration ?? 60,
            'slow_query_threshold' => $request->slow_query_threshold ?? 1000,
        ]);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Bắt đầu theo dõi SQL'
        ]);
    }

    /**
     * Danh sách slow queries
     */
    public function slowQueries(Request $request)
    {
        $queries = $this->sqlMonitorService->getSlowQueries(
            $request->limit ?? 100
        );

        return response()->json([
            'success' => true,
            'data' => $queries
        ]);
    }

    /**
     * Phân tích query
     */
    public function analyzeQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysis = $this->sqlMonitorService->analyzeQuery($request->query);

        return response()->json([
            'success' => true,
            'data' => [
                'execution_time' => $analysis['time'],
                'explain' => $analysis['explain'],
                'suggestions' => $analysis['suggestions'],
                'optimized_query' => $analysis['optimized'] ?? null,
            ]
        ]);
    }

    /**
     * Phát hiện SQL độc hại
     */
    public function detectMalicious(Request $request)
    {
        $detections = $this->sqlMonitorService->detectMaliciousQueries();

        return response()->json([
            'success' => true,
            'data' => $detections
        ]);
    }

    /**
     * Chặn query
     */
    public function blockQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query_pattern' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->sqlMonitorService->blockQueryPattern(
            $request->query_pattern,
            $request->reason
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Đã chặn pattern query' : 'Không thể chặn'
        ]);
    }
}