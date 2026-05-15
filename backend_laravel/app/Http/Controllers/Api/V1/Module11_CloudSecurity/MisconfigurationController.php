<?php

namespace App\Http\Controllers\Api\V1\Module11_CloudSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MisconfigurationService;

class MisconfigurationController extends Controller
{
    protected $misconfigService;

    public function __construct(MisconfigurationService $misconfigService)
    {
        $this->misconfigService = $misconfigService;
    }

    /**
     * Quét cấu hình sai
     */
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|in:aws,azure,gcp',
            'service' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->misconfigService->scanMisconfigurations(
            $request->provider,
            $request->service
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * CIS benchmarks
     */
    public function cisBenchmarks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|in:aws,azure,gcp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $benchmarks = $this->misconfigService->getCisBenchmarks($request->provider);

        return response()->json([
            'success' => true,
            'data' => $benchmarks
        ]);
    }

    /**
     * Tự động sửa lỗi
     */
    public function autoFix(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'finding_id' => 'required|string',
            'provider' => 'required|in:aws,azure,gcp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->misconfigService->autoFixMisconfiguration(
            $request->finding_id,
            $request->provider
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Đã tự động sửa lỗi' : 'Không thể tự động sửa'
        ]);
    }

    /**
     * Best practices
     */
    public function bestPractices(Request $request)
    {
        $practices = $this->misconfigService->getBestPractices();

        return response()->json([
            'success' => true,
            'data' => $practices
        ]);
    }
}