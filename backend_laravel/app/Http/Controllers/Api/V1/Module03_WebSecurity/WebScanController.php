<?php

namespace App\Http\Controllers\Api\V1\Module03_WebSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\WebScanService;

class WebScanController extends Controller
{
    protected $webScanService;

    public function __construct(WebScanService $webScanService)
    {
        $this->webScanService = $webScanService;
    }

    /**
     * Quét bảo mật website
     */
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'scan_type' => 'nullable|in:full,quick,custom',
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $scanId = $this->webScanService->startScan(
            $request->url,
            $request->scan_type ?? 'full',
            $request->options ?? [],
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'data' => [
                'scan_id' => $scanId,
                'url' => $request->url,
                'status' => 'started',
                'estimated_time' => $this->webScanService->getEstimatedTime($request->scan_type),
            ],
            'message' => 'Bắt đầu quét bảo mật'
        ]);
    }

    /**
     * Kết quả quét
     */
    public function result($scanId)
    {
        $result = $this->webScanService->getScanResult($scanId, auth()->id());

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy kết quả quét'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Danh sách các lần quét
     */
    public function history(Request $request)
    {
        $scans = $this->webScanService->getUserScans(auth()->id(), $request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $scans
        ]);
    }
}