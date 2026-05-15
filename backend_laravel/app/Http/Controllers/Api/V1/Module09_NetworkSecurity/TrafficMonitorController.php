<?php

namespace App\Http\Controllers\Api\V1\Module09_NetworkSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrafficMonitorService;

class TrafficMonitorController extends Controller
{
    protected $trafficService;

    public function __construct(TrafficMonitorService $trafficService)
    {
        $this->trafficService = $trafficService;
    }

    /**
     * Thống kê traffic
     */
    public function statistics(Request $request)
    {
        $stats = $this->trafficService->getStatistics([
            'interface' => $request->interface,
            'duration' => $request->duration ?? 60,
        ]);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Top connections
     */
    public function topConnections(Request $request)
    {
        $top = $this->trafficService->getTopConnections($request->limit ?? 10);

        return response()->json([
            'success' => true,
            'data' => $top
        ]);
    }

    /**
     * Phân tích traffic
     */
    public function analyze(Request $request)
    {
        $analysis = $this->trafficService->analyzeTraffic();

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Capture packets
     */
    public function capture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'interface' => 'nullable|string',
            'filter' => 'nullable|string',
            'duration' => 'nullable|integer|min=1|max=60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $capture = $this->trafficService->startCapture([
            'interface' => $request->interface ?? 'eth0',
            'filter' => $request->filter,
            'duration' => $request->duration ?? 10,
        ]);

        return response()->json([
            'success' => true,
            'data' => $capture
        ]);
    }

    /**
     * Phát hiện bất thường
     */
    public function anomalies(Request $request)
    {
        $anomalies = $this->trafficService->detectAnomalies();

        return response()->json([
            'success' => true,
            'data' => $anomalies
        ]);
    }
}