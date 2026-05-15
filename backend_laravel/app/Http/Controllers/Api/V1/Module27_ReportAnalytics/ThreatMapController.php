<?php

namespace App\Http\Controllers\Api\V1\Module27_ReportAnalytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ThreatMapService;

class ThreatMapController extends Controller
{
    protected $threatMapService;

    public function __construct(ThreatMapService $threatMapService)
    {
        $this->threatMapService = $threatMapService;
    }

    /**
     * Global map
     */
    public function globalMap(Request $request)
    {
        $map = $this->threatMapService->getGlobalMap();

        return response()->json([
            'success' => true,
            'data' => $map
        ]);
    }

    /**
     * Attack origins
     */
    public function attackOrigins(Request $request)
    {
        $origins = $this->threatMapService->getAttackOrigins([
            'period' => $request->period ?? '24h',
        ]);

        return response()->json([
            'success' => true,
            'data' => $origins
        ]);
    }

    /**
     * Target distribution
     */
    public function targetDistribution(Request $request)
    {
        $targets = $this->threatMapService->getTargetDistribution();

        return response()->json([
            'success' => true,
            'data' => $targets
        ]);
    }

    /**
     * Realtime attacks
     */
    public function realtimeAttacks()
    {
        $attacks = $this->threatMapService->getRealtimeAttacks();

        return response()->json([
            'success' => true,
            'data' => $attacks
        ]);
    }

    /**
     * Attack statistics
     */
    public function attackStatistics(Request $request)
    {
        $stats = $this->threatMapService->getAttackStatistics([
            'country' => $request->country,
            'type' => $request->type,
        ]);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}