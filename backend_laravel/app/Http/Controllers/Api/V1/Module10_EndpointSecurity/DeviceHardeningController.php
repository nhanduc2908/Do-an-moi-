<?php

namespace App\Http\Controllers\Api\V1\Module10_EndpointSecurity;

use App\Http\Controllers\Controller;
using Illuminate\Http\Request;
use App\Services\DeviceHardeningService;

class DeviceHardeningController extends Controller
{
    protected $hardeningService;

    public function __construct(DeviceHardeningService $hardeningService)
    {
        $this->hardeningService = $hardeningService;
    }

    /**
     * Quét cấu hình bảo mật
     */
    public function scanSecurity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->hardeningService->scanSecurityConfig($request->endpoint_id);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Áp dụng hardening
     */
    public function applyHardening(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint_id' => 'required|string',
            'profile' => 'required|in:basic,standard,high,cis,nis',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->hardeningService->applyHardening(
            $request->endpoint_id,
            $request->profile
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Áp dụng hardening thành công'
        ]);
    }

    /**
     * Danh sách hardening policies
     */
    public function policies()
    {
        $policies = $this->hardeningService->getPolicies();

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }

    /**
     * Compliance report
     */
    public function complianceReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint_id' => 'required|string',
            'standard' => 'nullable|in:cis,nist,iso27001,pcidss',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->hardeningService->generateComplianceReport(
            $request->endpoint_id,
            $request->standard ?? 'cis'
        );

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}