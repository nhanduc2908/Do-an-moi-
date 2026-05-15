<?php

namespace App\Http\Controllers\Api\V1\Module16_Compliance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Iso27001Service;

class Iso27001Controller extends Controller
{
    protected $isoService;

    public function __construct(Iso27001Service $isoService)
    {
        $this->isoService = $isoService;
    }

    /**
     * Annex A controls
     */
    public function annexControls(Request $request)
    {
        $controls = $this->isoService->getAnnexControls([
            'clause' => $request->clause,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $controls
        ]);
    }

    /**
     * Statement of Applicability (SoA)
     */
    public function statementOfApplicability()
    {
        $soa = $this->isoService->getStatementOfApplicability();

        return response()->json([
            'success' => true,
            'data' => $soa
        ]);
    }

    /**
     * Cập nhật SoA
     */
    public function updateSoa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'controls' => 'required|array',
            'controls.*.id' => 'required|string',
            'controls.*.applicable' => 'required|boolean',
            'controls.*.justification' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $soa = $this->isoService->updateStatementOfApplicability($request->controls);

        return response()->json([
            'success' => true,
            'data' => $soa,
            'message' => 'Cập nhật SoA thành công'
        ]);
    }

    /**
     * Risk assessment report
     */
    public function riskAssessmentReport()
    {
        $report = $this->isoService->getRiskAssessmentReport();

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Internal audit schedule
     */
    public function internalAuditSchedule()
    {
        $schedule = $this->isoService->getInternalAuditSchedule();

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    /**
     * Management review
     */
    public function managementReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'attendees' => 'required|array',
            'topics' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $review = $this->isoService->scheduleManagementReview($request->all());

        return response()->json([
            'success' => true,
            'data' => $review,
            'message' => 'Đã lên lịch management review'
        ]);
    }

    /**
     * Certification status
     */
    public function certificationStatus()
    {
        $status = $this->isoService->getCertificationStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Compliance score
     */
    public function complianceScore()
    {
        $score = $this->isoService->calculateComplianceScore();

        return response()->json([
            'success' => true,
            'data' => $score
        ]);
    }

    /**
     * Gap analysis
     */
    public function gapAnalysis()
    {
        $gaps = $this->isoService->performGapAnalysis();

        return response()->json([
            'success' => true,
            'data' => $gaps
        ]);
    }

    /**
     * Generate audit report
     */
    public function generateAuditReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required|string',
            'format' => 'nullable|in:pdf,docx,html',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->isoService->generateAuditReport(
            $request->audit_id,
            $request->format ?? 'pdf'
        );

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}