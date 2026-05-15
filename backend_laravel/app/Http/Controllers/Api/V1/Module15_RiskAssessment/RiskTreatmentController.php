<?php

namespace App\Http\Controllers\Api\V1\Module15_RiskAssessment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\RiskTreatmentService;

class RiskTreatmentController extends Controller
{
    protected $treatmentService;

    public function __construct(RiskTreatmentService $treatmentService)
    {
        $this->treatmentService = $treatmentService;
    }

    /**
     * Đề xuất treatment options
     */
    public function suggestTreatments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'risk_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $treatments = $this->treatmentService->suggestTreatments($request->risk_id);

        return response()->json([
            'success' => true,
            'data' => $treatments
        ]);
    }

    /**
     * Áp dụng treatment
     */
    public function applyTreatment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'risk_id' => 'required|string',
            'treatment_type' => 'required|in:mitigate,transfer,accept,avoid',
            'action_plan' => 'required|string',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $treatment = $this->treatmentService->applyTreatment($request->all());

        return response()->json([
            'success' => true,
            'data' => $treatment,
            'message' => 'Đã áp dụng treatment plan'
        ]);
    }

    /**
     * Danh sách treatments
     */
    public function listTreatments(Request $request)
    {
        $treatments = $this->treatmentService->getTreatments([
            'status' => $request->status,
            'type' => $request->type,
        ]);

        return response()->json([
            'success' => true,
            'data' => $treatments
        ]);
    }

    /**
     * Chi tiết treatment
     */
    public function treatmentDetail($id)
    {
        $treatment = $this->treatmentService->getTreatmentDetail($id);

        return response()->json([
            'success' => true,
            'data' => $treatment
        ]);
    }

    /**
     * Cập nhật treatment status
     */
    public function updateTreatmentStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $treatment = $this->treatmentService->updateStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'data' => $treatment,
            'message' => 'Cập nhật status thành công'
        ]);
    }

    /**
     * Residual risk
     */
    public function residualRisk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'risk_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $residual = $this->treatmentService->calculateResidualRisk($request->risk_id);

        return response()->json([
            'success' => true,
            'data' => $residual
        ]);
    }

    /**
     * Treatment effectiveness
     */
    public function effectiveness(Request $request)
    {
        $effectiveness = $this->treatmentService->measureEffectiveness();

        return response()->json([
            'success' => true,
            'data' => $effectiveness
        ]);
    }
}