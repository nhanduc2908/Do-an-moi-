<?php

namespace App\Http\Controllers\Api\V1\Module20_IotSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SensorMonitoringService;

class SensorMonitoringController extends Controller
{
    protected $sensorService;

    public function __construct(SensorMonitoringService $sensorService)
    {
        $this->sensorService = $sensorService;
    }

    /**
     * Sensor data
     */
    public function sensorData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'sensor_type' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $this->sensorService->getSensorData($request->all());

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Real-time feed
     */
    public function realtimeFeed($deviceId)
    {
        $feed = $this->sensorService->getRealtimeFeed($deviceId);

        return response()->json([
            'success' => true,
            'data' => $feed
        ]);
    }

    /**
     * Anomaly detection
     */
    public function detectAnomalies($deviceId)
    {
        $anomalies = $this->sensorService->detectAnomalies($deviceId);

        return response()->json([
            'success' => true,
            'data' => $anomalies
        ]);
    }

    /**
     * Set threshold
     */
    public function setThreshold(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'sensor_type' => 'required|string',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->sensorService->setThreshold($request->all());

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Cập nhật threshold thành công'
        ]);
    }

    /**
     * Sensor health
     */
    public function sensorHealth($deviceId)
    {
        $health = $this->sensorService->getSensorHealth($deviceId);

        return response()->json([
            'success' => true,
            'data' => $health
        ]);
    }
}