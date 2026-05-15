<?php

namespace App\Http\Controllers\Api\V1\Module18_SecurityAwareness;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PhishingSimulationService;

class PhishingSimulationController extends Controller
{
    protected $phishingService;

    public function __construct(PhishingSimulationService $phishingService)
    {
        $this->phishingService = $phishingService;
    }

    /**
     * Tạo campaign mới
     */
    public function createCampaign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'template_id' => 'required|string',
            'target_users' => 'required|array',
            'target_users.*' => 'string',
            'scheduled_at' => 'nullable|date',
            'track_clicks' => 'nullable|boolean',
            'track_credentials' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $campaign = $this->phishingService->createCampaign($request->all());

        return response()->json([
            'success' => true,
            'data' => $campaign,
            'message' => 'Tạo phishing campaign thành công'
        ]);
    }

    /**
     * Danh sách campaigns
     */
    public function campaigns(Request $request)
    {
        $campaigns = $this->phishingService->getCampaigns([
            'status' => $request->status,
            'start_date' => $request->start_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }

    /**
     * Campaign statistics
     */
    public function campaignStats($id)
    {
        $stats = $this->phishingService->getCampaignStatistics($id);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Launch campaign
     */
    public function launchCampaign($id)
    {
        $result = $this->phishingService->launchCampaign($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Campaign đã được launch' : 'Không thể launch campaign'
        ]);
    }

    /**
     * Phishing templates
     */
    public function templates(Request $request)
    {
        $templates = $this->phishingService->getTemplates([
            'type' => $request->type,
        ]);

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Create template
     */
    public function createTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'subject' => 'required|string',
            'content' => 'required|string',
            'sender_name' => 'required|string',
            'sender_email' => 'required|email',
            'type' => 'required|in:credential,malware,link,attachment',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $template = $this->phishingService->createTemplate($request->all());

        return response()->json([
            'success' => true,
            'data' => $template,
            'message' => 'Tạo template thành công'
        ]);
    }

    /**
     * User results
     */
    public function userResults(Request $request)
    {
        $results = $this->phishingService->getUserResults([
            'user_id' => $request->user_id,
            'campaign_id' => $request->campaign_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * User risk score
     */
    public function userRiskScore($userId)
    {
        $score = $this->phishingService->getUserRiskScore($userId);

        return response()->json([
            'success' => true,
            'data' => $score
        ]);
    }

    /**
     * Assign training for failed users
     */
    public function assignRemedialTraining(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|string',
            'course_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->phishingService->assignRemedialTraining(
            $request->campaign_id,
            $request->course_id
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Đã gán training cho ' . $result['assigned'] . ' users'
        ]);
    }

    /**
     * Generate report
     */
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|string',
            'format' => 'nullable|in:pdf,excel,csv',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->phishingService->generateReport(
            $request->campaign_id,
            $request->format ?? 'pdf'
        );

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}