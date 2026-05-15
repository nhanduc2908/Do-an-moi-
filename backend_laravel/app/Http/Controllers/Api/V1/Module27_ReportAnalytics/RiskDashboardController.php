<?php

namespace App\Http\Controllers\Api\V1\Module27_ReportAnalytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RiskDashboardService;

class RiskDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(RiskDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Dashboard data
     */
    public function dashboard(Request $request)
    {
        $dashboard = $this->dashboardService->getDashboardData([
            'period' => $request->period ?? 'month',
        ]);

        return response()->json([
            'success' => true,
            'data' => $dashboard
        ]);
    }

    /**
     * Risk heatmap
     */
    public function riskHeatmap(Request $request)
    {
        $heatmap = $this->dashboardService->getRiskHeatmap();

        return response()->json([
            'success' => true,
            'data' => $heatmap
        ]);
    }

    /**
     * Risk timeline
     */
    public function riskTimeline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'nullable|integer|min=7|max=365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $timeline = $this->dashboardService->getRiskTimeline($request->days ?? 30);

        return response()->json([
            'success' => true,
            'data' => $timeline
        ]);
    }

    /**
     * Top risks
     */
    public function topRisks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min=1|max=50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $topRisks = $this->dashboardService->getTopRisks($request->limit ?? 10);

        return response()->json([
            'success' => true,
            'data' => $topRisks
        ]);
    }

    /**
     * Risk metrics
     */
    public function riskMetrics()
    {
        $metrics = $this->dashboardService->getRiskMetrics();

        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }
}