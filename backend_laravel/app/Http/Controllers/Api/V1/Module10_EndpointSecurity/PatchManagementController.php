<?php

namespace App\Http\Controllers\Api\V1\Module10_EndpointSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PatchManagementService;

class PatchManagementController extends Controller
{
    protected $patchService;

    public function __construct(PatchManagementService $patchService)
    {
        $this->patchService = $patchService;
    }

    /**
     * Danh sách patches cần cập nhật
     */
    public function availablePatches(Request $request)
    {
        $patches = $this->patchService->getAvailablePatches([
            'severity' => $request->severity,
            'product' => $request->product,
        ]);

        return response()->json([
            'success' => true,
            'data' => $patches
        ]);
    }

    /**
     * Quét lỗ hổng cho endpoint
     */
    public function scanVulnerabilities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->patchService->scanEndpoint($request->endpoint_id);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Quét lỗ hổng hoàn tất'
        ]);
    }

    /**
     * Cài đặt patch
     */
    public function installPatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patch_id' => 'required|string',
            'endpoint_ids' => 'required|array',
            'endpoint_ids.*' => 'string',
            'schedule' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->patchService->installPatch(
            $request->patch_id,
            $request->endpoint_ids,
            $request->schedule
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Đã lên lịch cài đặt patch'
        ]);
    }

    /**
     * Lịch sử cài đặt
     */
    public function installationHistory(Request $request)
    {
        $history = $this->patchService->getInstallationHistory([
            'endpoint_id' => $request->endpoint_id,
            'status' => $request->status,
            'start_date' => $request->start_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Tự động cập nhật
     */
    public function autoUpdateConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enabled' => 'required|boolean',
            'schedule_time' => 'nullable|string',
            'severity_threshold' => 'nullable|in:critical,high,medium,low',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->patchService->updateAutoUpdateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình tự động thành công'
        ]);
    }

    /**
     * Thống kê patch
     */
    public function statistics()
    {
        $stats = $this->patchService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}