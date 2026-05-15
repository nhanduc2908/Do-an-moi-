<?php

namespace App\Http\Controllers\Api\V1\Module15_RiskAssessment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RiskMatrixService;

class RiskMatrixController extends Controller
{
    protected $matrixService;

    public function __construct(RiskMatrixService $matrixService)
    {
        $this->matrixService = $matrixService;
    }

    /**
     * Lấy risk matrix
     */
    public function getMatrix(Request $request)
    {
        $matrix = $this->matrixService->getRiskMatrix();

        return response()->json([
            'success' => true,
            'data' => $matrix
        ]);
    }

    /**
     * Đánh giá risk level
     */
    public function evaluateRisk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'likelihood' => 'required|in:very_low,low,medium,high,very_high',
            'impact' => 'required|in:very_low,low,medium,high,very_high',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $level = $this->matrixService->evaluateRiskLevel(
            $request->likelihood,
            $request->impact
        );

        return response()->json([
            'success' => true,
            'data' => $level
        ]);
    }

    /**
     * Cập nhật risk matrix config
     */
    public function updateConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matrix' => 'required|array',
            'matrix.*.*' => 'required|in:critical,high,medium,low',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->matrixService->updateConfig($request->matrix);

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật risk matrix thành công'
        ]);
    }

    /**
     * Risk acceptance criteria
     */
    public function acceptanceCriteria()
    {
        $criteria = $this->matrixService->getAcceptanceCriteria();

        return response()->json([
            'success' => true,
            'data' => $criteria
        ]);
    }

    /**
     * Cập nhật acceptance criteria
     */
    public function updateAcceptanceCriteria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'acceptable_risk_levels' => 'required|array',
            'acceptable_risk_levels.*' => 'in:low,medium,high,critical',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $criteria = $this->matrixService->updateAcceptanceCriteria($request->acceptable_risk_levels);

        return response()->json([
            'success' => true,
            'data' => $criteria,
            'message' => 'Cập nhật acceptance criteria thành công'
        ]);
    }
}