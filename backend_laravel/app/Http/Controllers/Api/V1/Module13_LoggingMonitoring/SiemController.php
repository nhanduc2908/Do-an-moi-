<?php

namespace App\Http\Controllers\Api\V1\Module13_LoggingMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SiemService;

class SiemController extends Controller
{
    protected $siemService;

    public function __construct(SiemService $siemService)
    {
        $this->siemService = $siemService;
    }

    /**
     * Tổng quan SIEM
     */
    public function dashboard(Request $request)
    {
        $dashboard = $this->siemService->getDashboard();

        return response()->json([
            'success' => true,
            'data' => $dashboard
        ]);
    }

    /**
     * Tìm kiếm logs
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'limit' => 'nullable|integer|min=1|max=10000',
            'offset' => 'nullable|integer|min=0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->siemService->searchLogs(
            $request->query,
            $request->start_time,
            $request->end_time,
            $request->limit ?? 100,
            $request->offset ?? 0
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Correlate events
     */
    public function correlate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rule_id' => 'nullable|string',
            'time_window' => 'nullable|integer|min=1|max=3600',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $correlations = $this->siemService->correlateEvents([
            'rule_id' => $request->rule_id,
            'time_window' => $request->time_window ?? 300,
        ]);

        return response()->json([
            'success' => true,
            'data' => $correlations
        ]);
    }

    /**
     * Correlation rules
     */
    public function correlationRules(Request $request)
    {
        $rules = $this->siemService->getCorrelationRules();

        return response()->json([
            'success' => true,
            'data' => $rules
        ]);
    }

    /**
     * Tạo correlation rule
     */
    public function createCorrelationRule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'description' => 'nullable|string',
            'condition' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'time_window' => 'required|integer|min=1|max=3600',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $rule = $this->siemService->createCorrelationRule($request->all());

        return response()->json([
            'success' => true,
            'data' => $rule,
            'message' => 'Tạo correlation rule thành công'
        ]);
    }

    /**
     * Data sources
     */
    public function dataSources()
    {
        $sources = $this->siemService->getDataSources();

        return response()->json([
            'success' => true,
            'data' => $sources
        ]);
    }

    /**
     * Thêm data source
     */
    public function addDataSource(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'type' => 'required|in:syslog,api,file,database',
            'config' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $source = $this->siemService->addDataSource($request->all());

        return response()->json([
            'success' => true,
            'data' => $source,
            'message' => 'Thêm data source thành công'
        ]);
    }

    /**
     * Thống kê SIEM
     */
    public function statistics(Request $request)
    {
        $stats = $this->siemService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}