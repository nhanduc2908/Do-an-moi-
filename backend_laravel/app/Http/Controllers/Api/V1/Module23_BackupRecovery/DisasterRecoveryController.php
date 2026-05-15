<?php

namespace App\Http\Controllers\Api\V1\Module23_BackupRecovery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DrPlan;
use App\Services\DisasterRecoveryService;

class DisasterRecoveryController extends Controller
{
    protected $drService;

    public function __construct(DisasterRecoveryService $drService)
    {
        $this->drService = $drService;
    }

    /**
     * Danh sách plans
     */
    public function plans(Request $request)
    {
        $plans = DrPlan::when($request->priority, function($query, $priority) {
                $query->where('priority', $priority);
            })
            ->orderBy('priority')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }

    /**
     * Tạo plan
     */
    public function createPlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'description' => 'required|string',
            'priority' => 'required|in:critical,high,medium,low',
            'rto_minutes' => 'required|integer|min=15|max=1440',
            'rpo_minutes' => 'required|integer|min=5|max=720',
            'steps' => 'required|array',
            'contacts' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $plan = $this->drService->createPlan($request->all());

        return response()->json([
            'success' => true,
            'data' => $plan,
            'message' => 'Tạo DR plan thành công'
        ]);
    }

    /**
     * Plan detail
     */
    public function planDetail($id)
    {
        $plan = DrPlan::with('steps', 'contacts')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $plan
        ]);
    }

    /**
     * Test plan
     */
    public function testPlan($id)
    {
        $plan = DrPlan::findOrFail($id);
        
        $result = $this->drService->testPlan($plan);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Test DR plan hoàn tất'
        ]);
    }

    /**
     * Execute plan
     */
    public function executePlan($id)
    {
        $plan = DrPlan::findOrFail($id);
        
        $execution = $this->drService->executePlan($plan);

        return response()->json([
            'success' => true,
            'data' => $execution,
            'message' => 'Đang thực thi DR plan'
        ]);
    }

    /**
     * Calculate RTO/RPO
     */
    public function calculateRtoRpo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'system_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $metrics = $this->drService->calculateRtoRpo($request->system_id);

        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }

    /**
     * Drill report
     */
    public function drillReport(Request $request)
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

        $report = $this->drService->generateDrillReport($request->plan_id);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}