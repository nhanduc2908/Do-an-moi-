<?php

namespace App\Http\Controllers\Api\V1\Module10_EndpointSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\UsbControlService;

class UsbControlController extends Controller
{
    protected $usbService;

    public function __construct(UsbControlService $usbService)
    {
        $this->usbService = $usbService;
    }

    /**
     * Danh sách USB devices
     */
    public function devices(Request $request)
    {
        $devices = $this->usbService->getDevices([
            'status' => $request->status,
            'endpoint_id' => $request->endpoint_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $devices
        ]);
    }

    /**
     * Chặn USB device
     */
    public function blockDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->usbService->blockDevice(
            $request->device_id,
            $request->reason
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'USB device đã bị chặn' : 'Không thể chặn device'
        ]);
    }

    /**
     * Cho phép USB device
     */
    public function allowDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->usbService->allowDevice($request->device_id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'USB device đã được cho phép' : 'Không thể cho phép device'
        ]);
    }

    /**
     * Whitelist USB device
     */
    public function whitelistDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|string',
            'product_id' => 'required|string',
            'serial_number' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->usbService->whitelistDevice($request->all());

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Thêm device vào whitelist thành công'
        ]);
    }

    /**
     * Cấu hình policy
     */
    public function updatePolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mode' => 'required|in:allow_all,block_all,whitelist_only,audit',
            'allow_read_only' => 'nullable|boolean',
            'encryption_required' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $policy = $this->usbService->updatePolicy($request->all());

        return response()->json([
            'success' => true,
            'data' => $policy,
            'message' => 'Cập nhật policy thành công'
        ]);
    }

    /**
     * USB activity log
     */
    public function activityLog(Request $request)
    {
        $logs = $this->usbService->getActivityLog([
            'endpoint_id' => $request->endpoint_id,
            'device_id' => $request->device_id,
            'action' => $request->action,
            'start_date' => $request->start_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}