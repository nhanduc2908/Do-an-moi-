<?php

namespace App\Http\Controllers\Api\V1\Module26_AIEngine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AI\ThreatDetectionAIService;

class ThreatDetectionController extends Controller
{
    protected $aiService;

    public function __construct(ThreatDetectionAIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Detect threat
     */
    public function detect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|string',
            'data_type' => 'required|in:log,network_traffic,process,file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->aiService->detect($request->data, $request->data_type);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Analyze threat
     */
    public function analyzeThreat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'threat_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysis = $this->aiService->analyze($request->threat_id);

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Predict threat
     */
    public function predictThreat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'historical_data' => 'required|array',
            'timeframe_hours' => 'required|integer|min=1|max=168',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $prediction = $this->aiService->predict($request->historical_data, $request->timeframe_hours);

        return response()->json([
            'success' => true,
            'data' => $prediction
        ]);
    }

    /**
     * Model status
     */
    public function modelStatus()
    {
        $status = $this->aiService->getModelStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Train model
     */
    public function trainModel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dataset' => 'required|string',
            'epochs' => 'nullable|integer|min=1|max=1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->aiService->train($request->dataset, $request->epochs ?? 100);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Training started'
        ]);
    }
}