<?php

namespace App\Http\Controllers\Api\V1\Module06_ApiSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiGatewayService;

class ApiGatewayController extends Controller
{
    protected $gatewayService;

    public function __construct(ApiGatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    /**
     * Lấy cấu hình gateway
     */
    public function getConfig()
    {
        $config = $this->gatewayService->getConfig();

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Cập nhật cấu hình gateway
     */
    public function updateConfig(Request $request)
    {
        $config = $this->gatewayService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình gateway thành công'
        ]);
    }

    /**
     * Danh sách API endpoints
     */
    public function getEndpoints(Request $request)
    {
        $endpoints = $this->gatewayService->getEndpoints();

        return response()->json([
            'success' => true,
            'data' => $endpoints
        ]);
    }

    /**
     * Thêm API endpoint
     */
    public function addEndpoint(Request $request)
    {
        $endpoint = $this->gatewayService->addEndpoint($request->all());

        return response()->json([
            'success' => true,
            'data' => $endpoint,
            'message' => 'Thêm endpoint thành công'
        ]);
    }

    /**
     * Xóa API endpoint
     */
    public function deleteEndpoint($id)
    {
        $result = $this->gatewayService->deleteEndpoint($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa endpoint thành công' : 'Xóa thất bại'
        ]);
    }

    /**
     * Thống kê gateway
     */
    public function getStatistics(Request $request)
    {
        $stats = $this->gatewayService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}