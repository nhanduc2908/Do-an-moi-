<?php

namespace App\Http\Controllers\Api\V1\Module15_RiskAssessment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RiskScoreService;

class RiskScoreController extends Controller
{
    protected $riskScoreService;

    public function __construct(RiskScoreService $riskScoreService)
    {
        $this->riskScoreService = $riskScoreService;
    }

    /**
     * Tính risk score cho asset
     */
    public function calculateAssetRisk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|string',
            'asset_type' => 'required|in:server,database,application,network,data',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $score = $this->riskScoreService->calculateAssetRisk(
            $request->asset_id,
            $request->asset_type
        );

        return response()->json([
            'success' => true,
            'data' => $score
        ]);
    }

    /**
     * Tổng quan risk score
     */
    public function overview(Request $request)
    {
        $overview = $this->riskScoreService->getRiskOverview();

        return response()->json([
            'success' => true,
            'data' => $overview
        ]);
    }

    /**
     * Risk trend
     */
    public function trend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'nullable|integer|min=7|max=365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $trend = $this->riskScoreService->getRiskTrend($request->days ?? 30);

        return response()->json([
            'success' => true,
            'data' => $trend
        ]);
    }

    /**
     * Top risky assets
     */
    public function topRiskyAssets(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min=1|max=50',
        ]);

        $assets = $this->riskScoreService->getTopRiskyAssets($request->limit ?? 10);

        return response()->json([
            'success' => true,
            'data' => $assets
        ]);
    }

    /**
     * Risk heatmap
     */
    public function heatmap(Request $request)
    {
        $heatmap = $this->riskScoreService->getRiskHeatmap();

        return response()->json([
            'success' => true,
            'data' => $heatmap
        ]);
    }

    /**
     * Risk breakdown by category
     */
    public function breakdownByCategory()
    {
        $breakdown = $this->riskScoreService->getRiskBreakdown();

        return response()->json([
            'success' => true,
            'data' => $breakdown
        ]);
    }
}