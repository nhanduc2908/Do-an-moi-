<?php

namespace App\Http\Controllers\Api\V1\Module27_ReportAnalytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SocDashboardService;

class SocDashboardController extends Controller
{
    protected $socService;

    public function __construct(SocDashboardService $socService)
    {
        $this->socService = $socService;
    }

    /**
     * Dashboard
     */
    public function dashboard(Request $request)
    {
        $dashboard = $this->socService->getDashboard([
            'time_range' => $request->time_range ?? '24h',
        ]);

        return response()->json([
            'success' => true,
            'data' => $dashboard
        ]);
    }

    /**
     * Incident metrics
     */
    public function incidentMetrics(Request $request)
    {
        $metrics = $this->socService->getIncidentMetrics([
            'period' => $request->period ?? 'week',
        ]);

        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }

    /**
     * Response time
     */
    public function responseTime(Request $request)
    {
        $responseTime = $this->socService->getResponseTimeStats();

        return response()->json([
            'success' => true,
            'data' => $responseTime
        ]);
    }

    /**
     * SLA metrics
     */
    public function slaMetrics(Request $request)
    {
        $sla = $this->socService->getSlaMetrics();

        return response()->json([
            'success' => true,
            'data' => $sla
        ]);
    }

    /**
     * Team performance
     */
    public function teamPerformance(Request $request)
    {
        $performance = $this->socService->getTeamPerformance([
            'period' => $request->period ?? 'month',
        ]);

        return response()->json([
            'success' => true,
            'data' => $performance
        ]);
    }
}