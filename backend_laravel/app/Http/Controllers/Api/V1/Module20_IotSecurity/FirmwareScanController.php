<?php

namespace App\Http\Controllers\Api\V1\Module20_IotSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\FirmwareScanService;

class FirmwareScanController extends Controller
{
    protected $firmwareService;

    public function __construct(FirmwareScanService $firmwareService)
    {
        $this->firmwareService = $firmwareService;
    }

    /**
     * Upload và quét firmware
     */
    public function uploadScan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firmware' => 'required|file|max:209715200', // 200MB
            'device_type' => 'required|string',
            'manufacturer' => 'nullable|string',
            'version' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $scanId = $this->firmwareService->uploadAndScan(
            $request->file('firmware'),
            $request->device_type,
            $request->manufacturer,
            $request->version
        );

        return response()->json([
            'success' => true,
            'data' => ['scan_id' => $scanId],
            'message' => 'Đang quét firmware'
        ]);
    }

    /**
     * Kết quả quét
     */
    public function scanResult($scanId)
    {
        $result = $this->firmwareService->getScanResult($scanId);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Phân tích binary
     */
    public function analyzeBinary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysis = $this->firmwareService->analyzeBinary($request->url);

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Extract filesystem
     */
    public function extractFilesystem($scanId)
    {
        $filesystem = $this->firmwareService->extractFilesystem($scanId);

        return response()->json([
            'success' => true,
            'data' => $filesystem
        ]);
    }

    /**
     * Find hardcoded secrets
     */
    public function findHardcodedSecrets($scanId)
    {
        $secrets = $this->firmwareService->findHardcodedSecrets($scanId);

        return response()->json([
            'success' => true,
            'data' => $secrets
        ]);
    }

    /**
     * CVE vulnerabilities
     */
    public function cveVulnerabilities($scanId)
    {
        $vulns = $this->firmwareService->getCveVulnerabilities($scanId);

        return response()->json([
            'success' => true,
            'data' => $vulns
        ]);
    }
}