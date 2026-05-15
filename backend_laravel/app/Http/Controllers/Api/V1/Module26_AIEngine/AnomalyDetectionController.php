
<?php

namespace App\Http\Controllers\Api\V1\Module26_AIEngine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AI\AnomalyDetectionService;

class AnomalyDetectionController extends Controller
{
    protected $anomalyService;

    public function __construct(AnomalyDetectionService $anomalyService)
    {
        $this->anomalyService = $anomalyService;
    }

    /**
     * Detect anomalies
     */
    public function detectAnomalies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'data_type' => 'required|in:network,user,log,behavior',
            'sensitivity' => 'nullable|in:low,medium,high',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->anomalyService->detect($request->all());

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Get baseline
     */
    public function baseline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_type' => 'required|in:network,user,log,behavior',
            'time_range' => 'nullable|integer|min=1|max=90',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $baseline = $this->anomalyService->getBaseline(
            $request->data_type,
            $request->time_range ?? 30
        );

        return response()->json([
            'success' => true,
            'data' => $baseline
        ]);
    }

    /**
     * Learn patterns
     */
    public function learnPatterns(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'training_data' => 'required|array',
            'data_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->anomalyService->learnPatterns($request->all());

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Learning completed'
        ]);
    }

    /**
     * Anomaly alerts
     */
    public function anomalyAlerts(Request $request)
    {
        $alerts = $this->anomalyService->getAlerts([
            'severity' => $request->severity,
            'status' => $request->status,
            'start_date' => $request->start_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }
}