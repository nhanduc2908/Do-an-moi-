<?php

namespace App\Http\Controllers\Api\V1\Module19_MobileSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DevicePolicyService;

class DevicePolicyController extends Controller
{
    protected $policyService;

    public function __construct(DevicePolicyService $policyService)
    {
        $this->policyService = $policyService;
    }

    /**
     * Danh sách policies
     */
    public function policies(Request $request)
    {
        $policies = $this->policyService->getPolicies([
            'platform' => $request->platform,
            'type' => $request->type,
        ]);

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }

    /**
     * Create policy
     */
    public function createPolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'description' => 'nullable|string',
            'platform' => 'required|in:ios,android,both',
            'password_min_length' => 'nullable|integer|min=4|max=12',
            'password_complexity' => 'nullable|in:numeric,alphanumeric,complex',
            'enable_encryption' => 'nullable|boolean',
            'allow_screen_capture' => 'nullable|boolean',
            'allow_usb_debugging' => 'nullable|boolean',
            'allow_unknown_sources' => 'nullable|boolean',
            'max_failed_attempts' => 'nullable|integer|min=3|max=10',
            'max_inactivity_minutes' => 'nullable|integer|min=1|max=60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $policy = $this->policyService->createPolicy($request->all());

        return response()->json([
            'success' => true,
            'data' => $policy,
            'message' => 'Tạo policy thành công'
        ]);
    }

    /**
     * Update policy
     */
    public function updatePolicy(Request $request, $id)
    {
        $policy = $this->policyService->updatePolicy($id, $request->all());

        return response()->json([
            'success' => true,
            'data' => $policy,
            'message' => 'Cập nhật policy thành công'
        ]);
    }

    /**
     * Delete policy
     */
    public function deletePolicy($id)
    {
        $result = $this->policyService->deletePolicy($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa policy thành công' : 'Xóa thất bại'
        ]);
    }

    /**
     * Assign policy to device
     */
    public function assignPolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'policy_id' => 'required|string',
            'device_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $assignment = $this->policyService->assignPolicy(
            $request->policy_id,
            $request->device_id
        );

        return response()->json([
            'success' => true,
            'data' => $assignment,
            'message' => 'Policy đã được gán'
        ]);
    }
}