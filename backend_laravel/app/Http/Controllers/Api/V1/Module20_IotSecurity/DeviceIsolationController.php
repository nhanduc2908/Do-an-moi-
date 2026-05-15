<?php

namespace App\Http\Controllers\Api\V1\Module20_IotSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DeviceIsolationService;

class DeviceIsolationController extends Controller
{
    protected $isolationService;

    public function __construct(DeviceIsolationService $isolationService)
    {
        $this->isolationService = $isolationService;
    }

    /**
     * Isolate device
     */
    public function isolate($deviceId)
    {
        $result = $this->isolationService->isolateDevice($deviceId);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Device đã được cách ly' : 'Cách ly thất bại'
        ]);
    }

    /**
     * Reconnect device
     */
    public function reconnect($deviceId)
    {
        $result = $this->isolationService->reconnectDevice($deviceId);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Device đã được kết nối lại' : 'Kết nối lại thất bại'
        ]);
    }

    /**
     * Status
     */
    public function status($deviceId)
    {
        $status = $this->isolationService->getStatus($deviceId);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Quarantine network
     */
    public function quarantineNetwork(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'network_id' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->isolationService->quarantineNetwork(
            $request->network_id,
            $request->reason
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Network đã được quarantine' : 'Quarantine thất bại'
        ]);
    }
}