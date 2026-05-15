<?php

namespace App\Http\Controllers\Api\V1\Module05_UrlSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\LinkSandboxService;

class LinkSandboxController extends Controller
{
    protected $sandboxService;

    public function __construct(LinkSandboxService $sandboxService)
    {
        $this->sandboxService = $sandboxService;
    }

    /**
     * Phân tích link trong môi trường an toàn
     */
    public function analyze(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'timeout' => 'nullable|integer|min=5|max=60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->sandboxService->analyze(
            $request->url,
            $request->timeout ?? 30
        );

        return response()->json([
            'success' => true,
            'data' => [
                'analysis_id' => $result['id'],
                'status' => $result['status'],
                'final_url' => $result['final_url'] ?? null,
                'redirect_count' => $result['redirect_count'] ?? 0,
                'http_status' => $result['http_status'] ?? null,
                'malicious' => $result['malicious'],
                'threats' => $result['threats'] ?? [],
                'screenshot' => $result['screenshot'] ?? null,
            ]
        ]);
    }

    /**
     * Lấy kết quả phân tích
     */
    public function getResult($analysisId)
    {
        $result = $this->sandboxService->getResult($analysisId);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Tạo link an toàn để xem trước
     */
    public function createSafePreview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'expires_in_minutes' => 'nullable|integer|min=1|max=1440',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $preview = $this->sandboxService->createSafePreview(
            $request->url,
            $request->expires_in_minutes ?? 60
        );

        return response()->json([
            'success' => true,
            'data' => [
                'preview_url' => $preview['url'],
                'expires_at' => $preview['expires_at'],
            ]
        ]);
    }
}