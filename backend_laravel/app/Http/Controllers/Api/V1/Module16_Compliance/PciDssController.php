<?php

namespace App\Http\Controllers\Api\V1\Module16_Compliance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PciDssService;

class PciDssController extends Controller
{
    protected $pciService;

    public function __construct(PciDssService $pciService)
    {
        $this->pciService = $pciService;
    }

    /**
     * Cardholder Data Environment (CDE)
     */
    public function cdeScope()
    {
        $cde = $this->pciService->getCdeScope();

        return response()->json([
            'success' => true,
            'data' => $cde
        ]);
    }

    /**
     * Requirement compliance status
     */
    public function requirementStatus(Request $request)
    {
        $status = $this->pciService->getRequirementStatus([
            'requirement_id' => $request->requirement_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * All requirements
     */
    public function requirements()
    {
        $requirements = $this->pciService->getAllRequirements();

        return response()->json([
            'success' => true,
            'data' => $requirements
        ]);
    }

    /**
     * Validation scan
     */
    public function validationScan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target' => 'required|string',
            'scan_type' => 'required|in:external,internal',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $scan = $this->pciService->runValidationScan(
            $request->target,
            $request->scan_type
        );

        return response()->json([
            'success' => true,
            'data' => $scan,
            'message' => 'Bắt đầu validation scan'
        ]);
    }

    /**
     * Self-Assessment Questionnaire (SAQ)
     */
    public function saq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'saq_type' => 'required|in:a,b,c,p2pe,d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $saq = $this->pciService->getSaq($request->saq_type);

        return response()->json([
            'success' => true,
            'data' => $saq
        ]);
    }

    /**
     * Submit SAQ
     */
    public function submitSaq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array',
            'attestation' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $submission = $this->pciService->submitSaq($request->answers, $request->attestation);

        return response()->json([
            'success' => true,
            'data' => $submission,
            'message' => 'Đã gửi SAQ'
        ]);
    }

    /**
     * ROC (Report on Compliance)
     */
    public function roc(Request $request)
    {
        $roc = $this->pciService->generateRoc();

        return response()->json([
            'success' => true,
            'data' => $roc
        ]);
    }

    /**
     * AOC (Attestation of Compliance)
     */
    public function aoc()
    {
        $aoc = $this->pciService->generateAoc();

        return response()->json([
            'success' => true,
            'data' => $aoc
        ]);
    }

    /**
     * Compliance metrics
     */
    public function complianceMetrics()
    {
        $metrics = $this->pciService->getComplianceMetrics();

        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }

    /**
     * Remediation tasks
     */
    public function remediationTasks()
    {
        $tasks = $this->pciService->getRemediationTasks();

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }
}