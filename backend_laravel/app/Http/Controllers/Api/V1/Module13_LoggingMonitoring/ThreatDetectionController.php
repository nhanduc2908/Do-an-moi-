<?php

namespace App\Http\Controllers\Api\V1\Module13_LoggingMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ThreatDetectionService;

class ThreatDetectionController extends Controller
{
    protected $threatService;

    public function __construct(ThreatDetectionService $threatService)
    {
        $this->threatService = $threatService;
    }

    /**
     * Phát hiện threat realtime
     */
    public function realtimeDetection()
    {
        $threats = $this->threatService->realtimeDetection();

        return response()->json([
            'success' => true,
            'data' => $threats
        ]);
    }

    /**
     * Phân tích threat
     */
    public function analyzeThreat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'indicator' => 'required|string',
            'type' => 'required|in:ip,domain,hash,url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysis = $this->threatService->analyzeThreat(
            $request->indicator,
            $request->type
        );

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Threat intelligence feeds
     */
    public function threatFeeds(Request $request)
    {
        $feeds = $this->threatService->getThreatFeeds();

        return response()->json([
            'success' => true,
            'data' => $feeds
        ]);
    }

    /**
     * Cập nhật threat feeds
     */
    public function updateFeeds()
    {
        $result = $this->threatService->updateThreatFeeds();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Cập nhật threat feeds thành công' : 'Cập nhật thất bại'
        ]);
    }

    /**
     * IoC (Indicators of Compromise)
     */
    public function iocs(Request $request)
    {
        $iocs = $this->threatService->getIocs([
            'type' => $request->type,
            'confidence' => $request->confidence,
        ]);

        return response()->json([
            'success' => true,
            'data' => $iocs
        ]);
    }

    /**
     * Thêm IoC
     */
    public function addIoc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'indicator' => 'required|string',
            'type' => 'required|in:ip,domain,hash,url,email',
            'confidence' => 'required|in:low,medium,high',
            'source' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $ioc = $this->threatService->addIoc($request->all());

        return response()->json([
            'success' => true,
            'data' => $ioc,
            'message' => 'Thêm IoC thành công'
        ]);
    }

    /**
     * MITRE ATT&CK mapping
     */
    public function mitreMapping(Request $request)
    {
        $techniques = $this->threatService->getMitreTechniques();

        return response()->json([
            'success' => true,
            'data' => $techniques
        ]);
    }
}