<?php

namespace App\Http\Controllers\Api\V1\Module12_ContainerSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DockerScanService;

class DockerScanController extends Controller
{
    protected $dockerService;

    public function __construct(DockerScanService $dockerService)
    {
        $this->dockerService = $dockerService;
    }

    /**
     * Quét image
     */
    public function scanImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_name' => 'required|string',
            'tag' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $scanId = $this->dockerService->scanImage(
            $request->image_name,
            $request->tag ?? 'latest'
        );

        return response()->json([
            'success' => true,
            'data' => ['scan_id' => $scanId],
            'message' => 'Bắt đầu quét Docker image'
        ]);
    }

    /**
     * Kết quả quét
     */
    public function scanResult($scanId)
    {
        $result = $this->dockerService->getScanResult($scanId);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Danh sách vulnerabilities
     */
    public function vulnerabilities(Request $request)
    {
        $vulns = $this->dockerService->getVulnerabilities([
            'severity' => $request->severity,
            'image_id' => $request->image_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $vulns
        ]);
    }

    /**
     * Docker daemon security
     */
    public function daemonSecurity()
    {
        $security = $this->dockerService->checkDaemonSecurity();

        return response()->json([
            'success' => true,
            'data' => $security
        ]);
    }

    /**
     * Running containers
     */
    public function runningContainers()
    {
        $containers = $this->dockerService->getRunningContainers();

        return response()->json([
            'success' => true,
            'data' => $containers
        ]);
    }

    /**
     * Container security
     */
    public function containerSecurity($containerId)
    {
        $security = $this->dockerService->checkContainerSecurity($containerId);

        return response()->json([
            'success' => true,
            'data' => $security
        ]);
    }
}