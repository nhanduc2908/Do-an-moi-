<?php

namespace App\Http\Controllers\Api\V1\Module05_UrlSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\UrlScanService;

class UrlScanController extends Controller
{
    protected $urlScanService;

    public function __construct(UrlScanService $urlScanService)
    {
        $this->urlScanService = $urlScanService;
    }

    /**
     * Quét URL
     */
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'deep_scan' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->urlScanService->scanUrl(
            $request->url,
            $request->deep_scan ?? false
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Lấy kết quả quét
     */
    public function getResult($scanId)
    {
        $result = $this->urlScanService->getScanResult($scanId);

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
     * Lịch sử quét
     */
    public function history(Request $request)
    {
        $history = $this->urlScanService->getUserHistory(auth()->id(), $request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}