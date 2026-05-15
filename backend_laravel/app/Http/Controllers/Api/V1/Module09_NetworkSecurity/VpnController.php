<?php

namespace App\Http\Controllers\Api\V1\Module09_NetworkSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\VpnService;

class VpnController extends Controller
{
    protected $vpnService;

    public function __construct(VpnService $vpnService)
    {
        $this->vpnService = $vpnService;
    }

    /**
     * Lấy cấu hình VPN
     */
    public function getConfig()
    {
        $config = $this->vpnService->getConfig();

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
        $config = $this->vpnService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình VPN thành công'
        ]);
    }

    /**
     * Danh sách kết nối
     */
    public function connections()
    {
        $connections = $this->vpnService->getConnections();

        return response()->json([
            'success' => true,
            'data' => $connections
        ]);
    }

    /**
     * Tạo client config
     */
    public function generateClientConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'protocol' => 'nullable|in:udp,tcp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->vpnService->generateClientConfig(
            $request->username,
            $request->protocol ?? 'udp'
        );

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Revoke client
     */
    public function revokeClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->vpnService->revokeClient($request->username);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Thu hồi client thành công' : 'Thu hồi client thất bại'
        ]);
    }

    /**
     * Thống kê
     */
    public function statistics()
    {
        $stats = $this->vpnService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}