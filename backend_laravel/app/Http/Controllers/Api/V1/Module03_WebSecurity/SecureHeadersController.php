<?php

namespace App\Http\Controllers\Api\V1\Module03_WebSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SecureHeadersService;

class SecureHeadersController extends Controller
{
    protected $headersService;

    public function __construct(SecureHeadersService $headersService)
    {
        $this->headersService = $headersService;
    }

    /**
     * Lấy cấu hình headers hiện tại
     */
    public function getConfig(Request $request)
    {
        $config = $this->headersService->getCurrentConfig();

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Cập nhật cấu hình headers
     */
    public function updateConfig(Request $request)
    {
        $config = $this->headersService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình thành công'
        ]);
    }

    /**
     * Kiểm tra headers của website
     */
    public function checkHeaders(Request $request)
    {
        $url = $request->url ?? config('app.url');

        $result = $this->headersService->checkHeaders($url);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Reset về mặc định
     */
    public function reset(Request $request)
    {
        $config = $this->headersService->resetToDefault();

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Reset cấu hình thành công'
        ]);
    }
}