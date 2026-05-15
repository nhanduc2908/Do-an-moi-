<?php

namespace App\Http\Controllers\Api\V1\Module04_PasswordSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordPolicy;
use App\Services\PasswordPolicyService;

class PasswordPolicyController extends Controller
{
    protected $policyService;

    public function __construct(PasswordPolicyService $policyService)
    {
        $this->policyService = $policyService;
    }

    /**
     * Lấy policy hiện tại
     */
    public function getPolicy()
    {
        $policy = $this->policyService->getActivePolicy();

        return response()->json([
            'success' => true,
            'data' => $policy
        ]);
    }

    /**
     * Cập nhật policy
     */
    public function updatePolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'min_length' => 'nullable|integer|min:6|max:128',
            'max_length' => 'nullable|integer|min:6|max:256',
            'require_uppercase' => 'nullable|boolean',
            'require_lowercase' => 'nullable|boolean',
            'require_numbers' => 'nullable|boolean',
            'require_special_chars' => 'nullable|boolean',
            'max_age_days' => 'nullable|integer|min:1|max:365',
            'prevent_reuse' => 'nullable|integer|min:0|max:24',
            'lockout_attempts' => 'nullable|integer|min:3|max:10',
            'lockout_duration_minutes' => 'nullable|integer|min:5|max:1440',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $policy = $this->policyService->updatePolicy($request->all());

        return response()->json([
            'success' => true,
            'data' => $policy,
            'message' => 'Cập nhật chính sách mật khẩu thành công'
        ]);
    }

    /**
     * Kiểm tra mật khẩu theo policy
     */
    public function validatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->policyService->validatePassword($request->password);

        return response()->json([
            'success' => $result['valid'],
            'data' => $result
        ]);
    }

    /**
     * Reset policy về mặc định
     */
    public function resetPolicy()
    {
        $policy = $this->policyService->resetToDefault();

        return response()->json([
            'success' => true,
            'data' => $policy,
            'message' => 'Reset chính sách thành công'
        ]);
    }
}