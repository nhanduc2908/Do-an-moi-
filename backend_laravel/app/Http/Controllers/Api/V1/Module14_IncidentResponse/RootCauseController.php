<?php

namespace App\Http\Controllers\Api\V1\Module14_IncidentResponse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RootCauseService;

class RootCauseController extends Controller
{
    protected $rootCauseService;

    public function __construct(RootCauseService $rootCauseService)
    {
        $this->rootCauseService = $rootCauseService;
    }

    /**
     * Phân tích root cause
     */
    public function analyze(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'incident_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysis = $this->rootCauseService->analyze($request->incident_id);

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * 5 Whys analysis
     */
    public function fiveWhys(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'problem' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysis = $this->rootCauseService->fiveWhys($request->problem);

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Fishbone diagram
     */
    public function fishbone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'incident_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $diagram = $this->rootCauseService->createFishboneDiagram($request->incident_id);

        return response()->json([
            'success' => true,
            'data' => $diagram
        ]);
    }

    /**
     * Recommendations
     */
    public function recommendations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'incident_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $recommendations = $this->rootCauseService->getRecommendations($request->incident_id);

        return response()->json([
            'success' => true,
            'data' => $recommendations
        ]);
    }
}