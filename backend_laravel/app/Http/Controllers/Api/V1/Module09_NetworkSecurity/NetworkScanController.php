<?php

namespace App\Http\Controllers\Api\V1\Module09_NetworkSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\NetworkScanService;

class NetworkScanController extends Controller
{
    protected $scanService;

    public function __construct(NetworkScanService $scanService)
    {
        $this->scanService = $scanService;
    }

    /**
     * Quét mạng
     */
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target' => 'required|string',
            'ports' => 'nullable|string',
            'scan_type' => 'nullable|in:syn,connect,udp,ping',
            'timeout' => 'nullable|integer|min=1|max=60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $scanId = $this->scanService->startScan([
            'target' => $request->target,
            'ports' => $request->ports ?? '1-1000',
            'scan_type' => $request->scan_type ?? 'syn',
            'timeout' => $request->timeout ?? 10,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'scan_id' => $scanId,
                'status' => 'started',
            ],
            'message' => 'Bắt đầu quét mạng'
        ]);
    }

    /**
     * Kết quả quét
     */
    public function result($scanId)
    {
        $result = $this->scanService->getResult($scanId);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Phát hiện thiết bị
     */
    public function discoverDevices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'network' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $devices = $this->scanService->discoverDevices($request->network);

        return response()->json([
            'success' => true,
            'data' => $devices
        ]);
    }

    /**
     * Lịch sử quét
     */
    public function history(Request $request)
    {
        $history = $this->scanService->getScanHistory();

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}