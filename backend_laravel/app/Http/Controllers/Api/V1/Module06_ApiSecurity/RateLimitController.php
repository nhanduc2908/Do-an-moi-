<?php

namespace App\Http\Controllers\Api\V1\Module06_ApiSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\RateLimitService;

class RateLimitController extends Controller
{
    protected $rateLimitService;

    public function __construct(RateLimitService $rateLimitService)
    {
        $this->rateLimitService = $rateLimitService;
    }

    /**
     * Lấy cấu hình rate limit
     */
    public function getConfig()
    {
        $config = $this->rateLimitService->getConfig();

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
        $validator = Validator::make($request->all(), [
            'default_limit' => 'nullable|integer|min=10|max=10000',
            'default_decay' => 'nullable|integer|min=1|max=60',
            'enable_headers' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->rateLimitService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình thành công'
        ]);
    }

    /**
     * Kiểm tra rate limit hiện tại
     */
    public function checkLimit(Request $request)
    {
        $key = $request->ip() . ':' . ($request->route()->getName() ?? 'default');
        
        $limit = $this->rateLimitService->getRemaining($key);

        return response()->json([
            'success' => true,
            'data' => $limit
        ]);
    }

    /**
     * Reset rate limit cho IP
     */
    public function resetIp(Request $request)
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

        $this->rateLimitService->resetForIp($request->ip);

        return response()->json([
            'success' => true,
            'message' => 'Reset rate limit thành công'
        ]);
    }

    /**
     * Danh sách IP bị rate limit
     */
    public function getThrottledIps(Request $request)
    {
        $ips = $this->rateLimitService->getThrottledIps();

        return response()->json([
            'success' => true,
            'data' => $ips
        ]);
    }
}