<?php

namespace App\Http\Controllers\Api\V1\Module15_RiskAssessment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ThreatAnalysisService;

class ThreatAnalysisController extends Controller
{
    protected $threatService;

    public function __construct(ThreatAnalysisService $threatService)
    {
        $this->threatService = $threatService;
    }

    /**
     * Phân tích threat
     */
    public function analyze(Request $request)
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

        $analysis = $this->threatService->analyzeThreat($request->threat_id);

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Danh sách threat actors
     */
    public function threatActors()
    {
        $actors = $this->threatService->getThreatActors();

        return response()->json([
            'success' => true,
            'data' => $actors
        ]);
    }

    /**
     * Danh sách attack vectors
     */
    public function attackVectors()
    {
        $vectors = $this->threatService->getAttackVectors();

        return response()->json([
            'success' => true,
            'data' => $vectors
        ]);
    }

    /**
     * Threat modeling
     */
    public function threatModeling(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|string',
            'methodology' => 'nullable|in:stride,dread,trike,vat',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $model = $this->threatService->createThreatModel(
            $request->application_id,
            $request->methodology ?? 'stride'
        );

        return response()->json([
            'success' => true,
            'data' => $model
        ]);
    }

    /**
     * Threat intelligence
     */
    public function threatIntelligence(Request $request)
    {
        $intel = $this->threatService->getThreatIntelligence();

        return response()->json([
            'success' => true,
            'data' => $intel
        ]);
    }

    /**
     * Attack surface analysis
     */
    public function attackSurface(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $surface = $this->threatService->analyzeAttackSurface($request->target);

        return response()->json([
            'success' => true,
            'data' => $surface
        ]);
    }
}