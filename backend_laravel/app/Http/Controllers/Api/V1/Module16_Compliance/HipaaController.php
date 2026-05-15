<?php

namespace App\Http\Controllers\Api\V1\Module16_Compliance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\HipaaService;

class HipaaController extends Controller
{
    protected $hipaaService;

    public function __construct(HipaaService $hipaaService)
    {
        $this->hipaaService = $hipaaService;
    }

    /**
     * PHI inventory
     */
    public function phiInventory()
    {
        $inventory = $this->hipaaService->getPhiInventory();

        return response()->json([
            'success' => true,
            'data' => $inventory
        ]);
    }

    /**
     * Privacy Rule compliance
     */
    public function privacyRuleCompliance()
    {
        $compliance = $this->hipaaService->getPrivacyRuleCompliance();

        return response()->json([
            'success' => true,
            'data' => $compliance
        ]);
    }

    /**
     * Security Rule compliance
     */
    public function securityRuleCompliance()
    {
        $compliance = $this->hipaaService->getSecurityRuleCompliance();

        return response()->json([
            'success' => true,
            'data' => $compliance
        ]);
    }

    /**
     * Breach notification
     */
    public function breachNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'affected_individuals' => 'required|integer',
            'phi_types' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $notification = $this->hipaaService->reportBreach($request->all());

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Đã ghi nhận breach notification'
        ]);
    }

    /**
     * BA agreements
     */
    public function baAgreements()
    {
        $agreements = $this->hipaaService->getBaAgreements();

        return response()->json([
            'success' => true,
            'data' => $agreements
        ]);
    }

    /**
     * Risk analysis
     */
    public function riskAnalysis()
    {
        $analysis = $this->hipaaService->performRiskAnalysis();

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Audit logs
     */
    public function auditLogs(Request $request)
    {
        $logs = $this->hipaaService->getAuditLogs([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Generate report
     */
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:risk,breach,audit,compliance',
            'format' => 'nullable|in:pdf,docx',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->hipaaService->generateReport(
            $request->report_type,
            $request->format ?? 'pdf'
        );

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}