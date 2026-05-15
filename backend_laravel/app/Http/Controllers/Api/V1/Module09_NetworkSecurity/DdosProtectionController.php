<?php

namespace App\Http\Controllers\Api\V1\Module09_NetworkSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DdosProtectionService;

class DdosProtectionController extends Controller
{
    protected $ddosService;

    public function __construct(DdosProtectionService $ddosService)
    {
        $this->ddosService = $ddosService;
    }

    /**
     * Lấy cấu hình bảo vệ DDoS
     */
    public function getConfig()
    {
        $config = $this->ddosService->getConfig();

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Cập nhật cấu hình
     */
    public function updateConfig(Request $request)
    {
        $config = $this->ddosService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình thành công'
        ]);
    }

    /**
     * Kích hoạt bảo vệ DDoS
     */
    public function enable()
    {
        $result = $this->ddosService->enable();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Bảo vệ DDoS đã được kích hoạt' : 'Không thể kích hoạt'
        ]);
    }

    /**
     * Phát hiện tấn công
     */
    public function detectAttack(Request $request)
    {
        $detection = $this->ddosService->detectAttack();

        return response()->json([
            'success' => true,
            'data' => $detection
        ]);
    }

    /**
     * Danh sách IP bị chặn
     */
    public function blockedIps()
    {
        $ips = $this->ddosService->getBlockedIps();

        return response()->json([
            'success' => true,
            'data' => $ips
        ]);
    }

    /**
     * Thống kê tấn công
     */
    public function attackStatistics()
    {
        $stats = $this->ddosService->getAttackStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Rate limiting config
     */
    public function rateLimitConfig(Request $request)
    {
        $config = $this->ddosService->getRateLimitConfig();

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Cập nhật rate limit
     */
    public function updateRateLimit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'requests_per_second' => 'nullable|integer|min=10|max=10000',
            'burst_multiplier' => 'nullable|integer|min=1|max=10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->ddosService->updateRateLimit($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật rate limit thành công'
        ]);
    }
}