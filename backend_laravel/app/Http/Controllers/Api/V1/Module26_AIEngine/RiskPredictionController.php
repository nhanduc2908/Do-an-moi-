<?php

namespace App\Http\Controllers\Api\V1\Module26_AIEngine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AI\RiskPredictionService;

class RiskPredictionController extends Controller
{
    protected $predictionService;

    public function __construct(RiskPredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    /**
     * Predict risk
     */
    public function predictRisk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|string',
            'timeframe_days' => 'nullable|integer|min=1|max=90',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $prediction = $this->predictionService->predictRisk(
            $request->asset_id,
            $request->timeframe_days ?? 30
        );

        return response()->json([
            'success' => true,
            'data' => $prediction
        ]);
    }

    /**
     * Predicted score
     */
    public function predictedScore($assetId)
    {
        $score = $this->predictionService->getPredictedScore($assetId);

        return response()->json([
            'success' => true,
            'data' => $score
        ]);
    }

    /**
     * Risk trend
     */
    public function riskTrend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|string',
            'days' => 'nullable|integer|min=7|max=365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $trend = $this->predictionService->getRiskTrend(
            $request->asset_id,
            $request->days ?? 30
        );

        return response()->json([
            'success' => true,
            'data' => $trend
        ]);
    }

    /**
     * What-if scenario
     */
    public function whatIfScenario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|string',
            'changes' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $scenario = $this->predictionService->whatIfScenario(
            $request->asset_id,
            $request->changes
        );

        return response()->json([
            'success' => true,
            'data' => $scenario
        ]);
    }
}