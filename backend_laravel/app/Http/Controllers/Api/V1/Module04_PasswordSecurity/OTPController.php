<?php

namespace App\Http\Controllers\Api\V1\Module04_PasswordSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\OTPService;

class OTPController extends Controller
{
    protected $otpService;

    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Gửi OTP
     */
    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string', // email hoặc số điện thoại
            'type' => 'required|in:email,sms',
            'purpose' => 'nullable|in:login,register,verify,reset_password,2fa',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->otpService->sendOTP(
            $request->identifier,
            $request->type,
            $request->purpose ?? 'verify'
        );

        return response()->json([
            'success' => $result['success'],
            'data' => [
                'reference_id' => $result['reference_id'] ?? null,
                'expires_in' => $result['expires_in'] ?? 300,
                'resend_after' => $result['resend_after'] ?? 60,
            ],
            'message' => $result['message']
        ]);
    }

    /**
     * Xác thực OTP
     */
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reference_id' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->otpService->verifyOTP(
            $request->reference_id,
            $request->code
        );

        return response()->json([
            'success' => $result['valid'],
            'data' => [
                'valid' => $result['valid'],
                'purpose' => $result['purpose'] ?? null,
                'identifier' => $result['identifier'] ?? null,
            ],
            'message' => $result['message']
        ]);
    }
}