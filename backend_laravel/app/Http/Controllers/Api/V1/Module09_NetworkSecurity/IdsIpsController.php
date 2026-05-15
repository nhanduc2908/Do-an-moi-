<?php

namespace App\Http\Controllers\Api\V1\Module09_NetworkSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IdsIpsService;

class IdsIpsController extends Controller
{
    protected $idsIpsService;

    public function __construct(IdsIpsService $idsIpsService)
    {
        $this->idsIpsService = $idsIpsService;
    }

    /**
     * Lấy cấu hình IDS/IPS
     */
    public function getConfig()
    {
        $config = $this->idsIpsService->getConfig();

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
        $config = $this->idsIpsService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình thành công'
        ]);
    }

    /**
     * Danh sách alerts
     */
    public function alerts(Request $request)
    {
        $alerts = $this->idsIpsService->getAlerts([
            'severity' => $request->severity,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * Kích hoạt IPS mode
     */
    public function enableIps(Request $request)
    {
        $result = $this->idsIpsService->enableIps();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'IPS mode đã được kích hoạt' : 'Không thể kích hoạt IPS'
        ]);
    }

    /**
     * Danh sách signatures
     */
    public function signatures(Request $request)
    {
        $signatures = $this->idsIpsService->getSignatures();

        return response()->json([
            'success' => true,
            'data' => $signatures
        ]);
    }

    /**
     * Cập nhật signatures
     */
    public function updateSignatures()
    {
        $result = $this->idsIpsService->updateSignatures();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Cập nhật signatures thành công' : 'Cập nhật signatures thất bại'
        ]);
    }

    /**
     * Thống kê
     */
    public function statistics()
    {
        $stats = $this->idsIpsService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}