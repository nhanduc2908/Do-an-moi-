<?php

namespace App\Http\Controllers\Api\V1\Module04_PasswordSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\BruteForceProtectionService;

class BruteForceController extends Controller
{
    protected $bruteForceService;

    public function __construct(BruteForceProtectionService $bruteForceService)
    {
        $this->bruteForceService = $bruteForceService;
    }

    /**
     * Lấy thông tin bảo vệ
     */
    public function getProtectionStatus(Request $request)
    {
        $status = $this->bruteForceService->getStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Cập nhật cấu hình
     */
    public function updateConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'max_attempts' => 'nullable|integer|min:3|max:20',
            'decay_minutes' => 'nullable|integer|min:1|max:60',
            'lockout_duration' => 'nullable|integer|min:5|max:1440',
            'enable_captcha_after' => 'nullable|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->bruteForceService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình thành công'
        ]);
    }

    /**
     * Danh sách IP bị chặn
     */
    public function getBlockedIps(Request $request)
    {
        $ips = $this->bruteForceService->getBlockedIps();

        return response()->json([
            'success' => true,
            'data' => $ips
        ]);
    }

    /**
     * Mở khóa IP
     */
    public function unblockIp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ip' => 'required|ip',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->bruteForceService->unblockIp($request->ip);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Mở khóa IP thành công' : 'IP không tồn tại trong danh sách chặn'
        ]);
    }

    /**
     * Thống kê tấn công
     */
    public function getStatistics(Request $request)
    {
        $stats = $this->bruteForceService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Reset attempts cho user
     */
    public function resetUserAttempts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->bruteForceService->resetUserAttempts($request->email);

        return response()->json([
            'success' => $result,
            'message' => 'Reset attempts thành công'
        ]);
    }
}