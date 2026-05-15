<?php

namespace App\Http\Controllers\Api\V1\Module10_EndpointSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AntivirusService;

class AntivirusController extends Controller
{
    protected $avService;

    public function __construct(AntivirusService $avService)
    {
        $this->avService = $avService;
    }

    /**
     * Quét file
     */
    public function scanFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:51200', // Max 50MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');
        $result = $this->avService->scanFile($file);

        return response()->json([
            'success' => true,
            'data' => [
                'is_infected' => $result['infected'],
                'malware_name' => $result['malware_name'] ?? null,
                'scan_time' => $result['scan_time'],
            ]
        ]);
    }

    /**
     * Quét thư mục
     */
    public function scanDirectory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
            'recursive' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->avService->scanDirectory(
            $request->path,
            $request->recursive ?? true
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Cập nhật virus definitions
     */
    public function updateDefinitions()
    {
        $result = $this->avService->updateDefinitions();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Cập nhật definitions thành công' : 'Cập nhật thất bại'
        ]);
    }

    /**
     * Lấy thông tin antivirus
     */
    public function info()
    {
        $info = $this->avService->getInfo();

        return response()->json([
            'success' => true,
            'data' => $info
        ]);
    }

    /**
     * Quét realtime status
     */
    public function realtimeStatus()
    {
        $status = $this->avService->getRealtimeStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Kích hoạt realtime protection
     */
    public function enableRealtime()
    {
        $result = $this->avService->enableRealtimeProtection();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Realtime protection đã được kích hoạt' : 'Không thể kích hoạt'
        ]);
    }

    /**
     * Lịch sử quét
     */
    public function scanHistory(Request $request)
    {
        $history = $this->avService->getScanHistory($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}