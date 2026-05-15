<?php

namespace App\Http\Controllers\Api\V1\Module27_ReportAnalytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ExecutiveReportService;

class ExecutiveReportController extends Controller
{
    protected $reportService;

    public function __construct(ExecutiveReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Generate report
     */
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'required|in:month,quarter,year',
            'format' => 'nullable|in:pdf,ppt,docx',
            'sections' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->reportService->generate($request->all());

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Templates
     */
    public function templates()
    {
        $templates = $this->reportService->getTemplates();

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Schedule report
     */
    public function scheduleReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|string',
            'schedule' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule = $this->reportService->schedule($request->all());

        return response()->json([
            'success' => true,
            'data' => $schedule,
            'message' => 'Report scheduled'
        ]);
    }

    /**
     * Report history
     */
    public function reportHistory(Request $request)
    {
        $history = $this->reportService->getHistory([
            'report_type' => $request->report_type,
            'start_date' => $request->start_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Download report
     */
    public function downloadReport($id)
    {
        $download = $this->reportService->download($id);

        return response()->json([
            'success' => true,
            'data' => $download
        ]);
    }
}