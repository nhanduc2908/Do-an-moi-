<?php

namespace App\Http\Controllers\Api\V1\Module12_ContainerSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ContainerRuntimeService;

class ContainerRuntimeController extends Controller
{
    protected $runtimeService;

    public function __construct(ContainerRuntimeService $runtimeService)
    {
        $this->runtimeService = $runtimeService;
    }

    /**
     * Runtime security
     */
    public function runtimeSecurity()
    {
        $security = $this->runtimeService->checkRuntimeSecurity();

        return response()->json([
            'success' => true,
            'data' => $security
        ]);
    }

    /**
     * Process monitoring
     */
    public function processMonitor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'container_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $processes = $this->runtimeService->monitorProcesses($request->container_id);

        return response()->json([
            'success' => true,
            'data' => $processes
        ]);
    }

    /**
     * File system monitoring
     */
    public function fsMonitor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'container_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $fs = $this->runtimeService->monitorFileSystem($request->container_id);

        return response()->json([
            'success' => true,
            'data' => $fs
        ]);
    }

    /**
     * Network monitoring
     */
    public function networkMonitor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'container_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $network = $this->runtimeService->monitorNetwork($request->container_id);

        return response()->json([
            'success' => true,
            'data' => $network
        ]);
    }

    /**
     * Stop suspicious container
     */
    public function stopContainer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'container_id' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->runtimeService->stopContainer(
            $request->container_id,
            $request->reason
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Container đã được dừng' : 'Không thể dừng container'
        ]);
    }
}