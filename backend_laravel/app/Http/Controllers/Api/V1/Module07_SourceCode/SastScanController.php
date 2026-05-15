<?php

namespace App\Http\Controllers\Api\V1\Module07_SourceCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SastScanService;

class SastScanController extends Controller
{
    protected $sastService;

    public function __construct(SastScanService $sastService)
    {
        $this->sastService = $sastService;
    }

    /**
     * Quét mã nguồn
     */
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository_url' => 'required|url',
            'branch' => 'nullable|string',
            'language' => 'nullable|string',
            'scan_rules' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $scanId = $this->sastService->startScan([
            'repository_url' => $request->repository_url,
            'branch' => $request->branch ?? 'main',
            'language' => $request->language,
            'scan_rules' => $request->scan_rules,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'scan_id' => $scanId,
                'status' => 'started',
            ],
            'message' => 'Bắt đầu quét mã nguồn'
        ]);
    }

    /**
     * Kết quả quét
     */
    public function result($scanId)
    {
        $result = $this->sastService->getResult($scanId);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy kết quả'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Danh sách lỗ hổng
     */
    public function vulnerabilities(Request $request)
    {
        $vulnerabilities = $this->sastService->getVulnerabilities([
            'severity' => $request->severity,
            'status' => $request->status,
            'scan_id' => $request->scan_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $vulnerabilities
        ]);
    }

    /**
     * Lịch sử quét
     */
    public function history(Request $request)
    {
        $history = $this->sastService->getScanHistory(auth()->id(), $request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}