<?php

namespace App\Http\Controllers\Api\V1\Module25_PenetrationTesting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SocialEngineeringService;

class SocialEngineeringController extends Controller
{
    protected $seService;

    public function __construct(SocialEngineeringService $seService)
    {
        $this->seService = $seService;
    }

    /**
     * Create campaign
     */
    public function createCampaign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'type' => 'required|in:phishing,vishing,smishing,physical',
            'targets' => 'required|array',
            'scenario' => 'required|string',
            'scheduled_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $campaign = $this->seService->createCampaign($request->all());

        return response()->json([
            'success' => true,
            'data' => $campaign,
            'message' => 'Tạo social engineering campaign thành công'
        ]);
    }

    /**
     * List campaigns
     */
    public function campaigns(Request $request)
    {
        $campaigns = $this->seService->getCampaigns([
            'type' => $request->type,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }

    /**
     * Launch campaign
     */
    public function launchCampaign($id)
    {
        $result = $this->seService->launchCampaign($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Campaign launched' : 'Launch failed'
        ]);
    }

    /**
     * Campaign results
     */
    public function campaignResults($id)
    {
        $results = $this->seService->getCampaignResults($id);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * Generate report
     */
    public function generateReport($id)
    {
        $report = $this->seService->generateReport($id);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}