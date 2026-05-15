<?php

namespace App\Http\Controllers\Api\V1\Module18_SecurityAwareness;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AwarenessReportService;

class AwarenessReportController extends Controller
{
    protected $reportService;

    public function __construct(AwarenessReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Tổng quan awareness
     */
    public function overview()
    {
        $overview = $this->reportService->getOverview();

        return response()->json([
            'success' => true,
            'data' => $overview
        ]);
    }

    /**
     * Training completion report
     */
    public function trainingCompletion(Request $request)
    {
        $report = $this->reportService->getTrainingCompletion([
            'department' => $request->department,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Phishing simulation report
     */
    public function phishingReport(Request $request)
    {
        $report = $this->reportService->getPhishingReport([
            'campaign_id' => $request->campaign_id,
            'period' => $request->period ?? 'month',
        ]);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Quiz performance report
     */
    public function quizPerformance(Request $request)
    {
        $report = $this->reportService->getQuizPerformance([
            'quiz_id' => $request->quiz_id,
            'department' => $request->department,
        ]);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Risk reduction metrics
     */
    public function riskReduction()
    {
        $metrics = $this->reportService->getRiskReductionMetrics();

        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }

    /**
     * Department comparison
     */
    public function departmentComparison()
    {
        $comparison = $this->reportService->getDepartmentComparison();

        return response()->json([
            'success' => true,
            'data' => $comparison
        ]);
    }

    /**
     * Generate executive report
     */
    public function executiveReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'required|in:month,quarter,year',
            'format' => 'nullable|in:pdf,ppt,docx',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->reportService->generateExecutiveReport(
            $request->period,
            $request->format ?? 'pdf'
        );

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Export raw data
     */
    public function exportData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:training,phishing,quiz,all',
            'format' => 'required|in:csv,excel,json',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $this->reportService->exportData($request->all());

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}