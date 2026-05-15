<?php

namespace App\Http\Controllers\Api\V1\Module19_MobileSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\RemoteWipeService;
use App\Models\MobileDevice;
use App\Models\WipeLog;

class RemoteWipeController extends Controller
{
    protected $wipeService;

    public function __construct(RemoteWipeService $wipeService)
    {
        $this->wipeService = $wipeService;
    }

    /**
     * Xóa toàn bộ dữ liệu trên thiết bị
     */
    public function wipeDevice(Request $request, $deviceId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string',
            'confirm' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $device = MobileDevice::where('user_id', auth()->id())
            ->orWhere('managed_by', auth()->id())
            ->findOrFail($deviceId);

        $result = $this->wipeService->wipeDevice($device, [
            'reason' => $request->reason ?? 'User initiated wipe',
            'initiated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'wipe_id' => $result['wipe_id'],
                'device_id' => $deviceId,
                'status' => $result['status'],
                'estimated_completion' => $result['estimated_completion'],
            ],
            'message' => 'Đã gửi lệnh wipe thiết bị'
        ]);
    }

    /**
     * Xóa dữ liệu trong secure container
     */
    public function wipeContainer(Request $request, $containerId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string',
            'preserve_metadata' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->wipeService->wipeContainer($containerId, [
            'reason' => $request->reason ?? 'Container wipe',
            'preserve_metadata' => $request->preserve_metadata ?? false,
            'initiated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Đã xóa secure container'
        ]);
    }

    /**
     * Xóa toàn bộ dữ liệu doanh nghiệp
     */
    public function wipeEnterprise(Request $request, $enterpriseId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string',
            'wipe_all_devices' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->wipeService->wipeEnterprise($enterpriseId, [
            'reason' => $request->reason,
            'wipe_all_devices' => $request->wipe_all_devices ?? true,
            'initiated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Đã bắt đầu quá trình wipe dữ liệu doanh nghiệp'
        ]);
    }

    /**
     * Trạng thái wipe
     */
    public function wipeStatus($wipeId)
    {
        $status = $this->wipeService->getWipeStatus($wipeId);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Lịch sử wipe
     */
    public function wipeHistory(Request $request)
    {
        $history = WipeLog::with('initiatedBy', 'device')
            ->when($request->device_id, function($query, $deviceId) {
                $query->where('device_id', $deviceId);
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Hủy lệnh wipe (nếu có thể)
     */
    public function cancelWipe($wipeId)
    {
        $result = $this->wipeService->cancelWipe($wipeId);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Đã hủy lệnh wipe' : 'Không thể hủy lệnh wipe'
        ]);
    }

    /**
     * Xóa từ xa (có báo trước)
     */
    public function scheduledWipe(Request $request, $deviceId)
    {
        $validator = Validator::make($request->all(), [
            'scheduled_at' => 'required|date|after:now',
            'reason' => 'required|string',
            'notify_user' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $device = MobileDevice::findOrFail($deviceId);

        $result = $this->wipeService->scheduleWipe($device, [
            'scheduled_at' => $request->scheduled_at,
            'reason' => $request->reason,
            'notify_user' => $request->notify_user ?? true,
            'initiated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Đã lên lịch wipe thiết bị vào lúc ' . $request->scheduled_at
        ]);
    }

    /**
     * Thống kê wipe
     */
    public function wipeStatistics(Request $request)
    {
        $stats = $this->wipeService->getStatistics([
            'period' => $request->period ?? 'month',
        ]);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}