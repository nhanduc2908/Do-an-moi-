<?php

namespace App\Http\Controllers\Api\V1\Module27_ReportAnalytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ComplianceDashboardService;

class ComplianceDashboardController extends Controller
{
    protected $complianceService;

    public function __construct(ComplianceDashboardService $complianceService)
    {
        $this->complianceService = $complianceService;
    }

    /**
     * Dashboard
     */
    public function dashboard(Request $request)
    {
        $dashboard = $this->complianceService->getDashboard([
            'framework' => $request->framework,
        ]);

        return response()->json([
            'success' => true,
            'data' => $dashboard
        ]);
    }

    /**
     * Framework status
     */
    public function frameworkStatus(Request $request)
    {
        $status = $this->complianceService->getFrameworkStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Gap analysis
     */
    public function gapAnalysis(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'framework' => 'required|in:iso27001,gdpr,pcidss,hipaa,nist',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $gaps = $this->complianceService->getGapAnalysis($request->framework);

        return response()->json([
            'success' => true,
            'data' => $gaps
        ]);
    }

    /**
     * Audit readiness
     */
    public function auditReadiness(Request $request)
    {
        $readiness = $this->complianceService->getAuditReadiness();

        return response()->json([
            'success' => true,
            'data' => $readiness
        ]);
    }

    /**
     * Compliance calendar
     */
    public function complianceCalendar(Request $request)
    {
        $calendar = $this->complianceService->getComplianceCalendar([
            'year' => $request->year ?? date('Y'),
        ]);

        return response()->json([
            'success' => true,
            'data' => $calendar
        ]);
    }
}