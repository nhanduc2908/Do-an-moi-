<?php

namespace App\Http\Controllers\Api\V1\Module22_EmailSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PhishingReport;
use App\Services\PhishingEmailService;

class PhishingEmailController extends Controller
{
    protected $phishingService;

    public function __construct(PhishingEmailService $phishingService)
    {
        $this->phishingService = $phishingService;
    }

    /**
     * Report phishing
     */
    public function reportPhishing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_content' => 'required|string',
            'sender' => 'required|email',
            'subject' => 'required|string',
            'reported_by' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->phishingService->report($request->all());

        return response()->json([
            'success' => true,
            'data' => $report,
            'message' => 'Đã ghi nhận báo cáo phishing'
        ]);
    }

    /**
     * Danh sách reports
     */
    public function listReports(Request $request)
    {
        $reports = PhishingReport::with('analyzer')
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    /**
     * Analyze phishing
     */
    public function analyzePhishing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysis = $this->phishingService->analyze($request->report_id);

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Phishing campaigns
     */
    public function campaigns(Request $request)
    {
        $campaigns = $this->phishingService->getCampaigns([
            'active' => $request->active,
        ]);

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }

    /**
     * Simulate phishing
     */
    public function simulatePhishing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target_emails' => 'required|array',
            'template_id' => 'required|string',
            'scheduled_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $simulation = $this->phishingService->simulate($request->all());

        return response()->json([
            'success' => true,
            'data' => $simulation,
            'message' => 'Bắt đầu mô phỏng phishing'
        ]);
    }

    /**
     * Statistics
     */
    public function statistics(Request $request)
    {
        $stats = $this->phishingService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}