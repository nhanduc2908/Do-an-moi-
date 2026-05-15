<?php

namespace App\Http\Controllers\Api\V1\Module14_IncidentResponse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\RecoveryService;

class RecoveryController extends Controller
{
    protected $recoveryService;

    public function __construct(RecoveryService $recoveryService)
    {
        $this->recoveryService = $recoveryService;
    }

    /**
     * Khôi phục hệ thống
     */
    public function recover(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'incident_id' => 'required|string',
            'recovery_type' => 'required|in:backup,snapshot,clean_reinstall',
            'target_systems' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $recoveryId = $this->recoveryService->startRecovery($request->all());

        return response()->json([
            'success' => true,
            'data' => ['recovery_id' => $recoveryId],
            'message' => 'Bắt đầu quá trình khôi phục'
        ]);
    }

    /**
     * Trạng thái khôi phục
     */
    public function recoveryStatus($recoveryId)
    {
        $status = $this->recoveryService->getRecoveryStatus($recoveryId);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Xác minh khôi phục
     */
    public function verifyRecovery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recovery_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->recoveryService->verifyRecovery($request->recovery_id);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Danh sách recovery points
     */
    public function recoveryPoints(Request $request)
    {
        $points = $this->recoveryService->getRecoveryPoints();

        return response()->json([
            'success' => true,
            'data' => $points
        ]);
    }

    /**
     * Test recovery plan
     */
    public function testPlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->recoveryService->testRecoveryPlan($request->plan_id);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}