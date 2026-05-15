<?php

namespace App\Http\Controllers\Api\V1\Module20_IotSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IotTrafficService;

class IotTrafficController extends Controller
{
    protected $trafficService;

    public function __construct(IotTrafficService $trafficService)
    {
        $this->trafficService = $trafficService;
    }

    /**
     * Monitor traffic
     */
    public function monitor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'duration' => 'nullable|integer|min=5|max=300',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $monitoring = $this->trafficService->startMonitoring(
            $request->device_id,
            $request->duration ?? 60
        );

        return response()->json([
            'success' => true,
            'data' => $monitoring
        ]);
    }

    /**
     * Traffic analysis
     */
    public function analyze($deviceId)
    {
        $analysis = $this->trafficService->analyzeTraffic($deviceId);

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Detect anomalies
     */
    public function detectAnomalies($deviceId)
    {
        $anomalies = $this->trafficService->detectAnomalies($deviceId);

        return response()->json([
            'success' => true,
            'data' => $anomalies
        ]);
    }

    /**
     * Block suspicious traffic
     */
    public function blockTraffic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'ip_address' => 'required|ip',
            'port' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->trafficService->blockTraffic(
            $request->device_id,
            $request->ip_address,
            $request->port
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Đã chặn traffic' : 'Chặn traffic thất bại'
        ]);
    }

    /**
     * Traffic patterns
     */
    public function trafficPatterns($deviceId)
    {
        $patterns = $this->trafficService->getTrafficPatterns($deviceId);

        return response()->json([
            'success' => true,
            'data' => $patterns
        ]);
    }

    /**
     * Baseline
     */
    public function createBaseline($deviceId)
    {
        $baseline = $this->trafficService->createBaseline($deviceId);

        return response()->json([
            'success' => true,
            'data' => $baseline,
            'message' => 'Đã tạo baseline'
        ]);
    }
}