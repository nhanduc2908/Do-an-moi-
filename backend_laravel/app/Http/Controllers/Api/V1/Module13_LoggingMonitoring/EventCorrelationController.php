<?php

namespace App\Http\Controllers\Api\V1\Module13_LoggingMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EventCorrelationService;

class EventCorrelationController extends Controller
{
    protected $correlationService;

    public function __construct(EventCorrelationService $correlationService)
    {
        $this->correlationService = $correlationService;
    }

    /**
     * Correlate events
     */
    public function correlate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'events' => 'required|array',
            'events.*' => 'array',
            'time_window' => 'nullable|integer|min=1|max=3600',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->correlationService->correlate(
            $request->events,
            $request->time_window ?? 300
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Correlation patterns
     */
    public function patterns()
    {
        $patterns = $this->correlationService->getPatterns();

        return response()->json([
            'success' => true,
            'data' => $patterns
        ]);
    }

    /**
     * Tạo correlation pattern
     */
    public function createPattern(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'description' => 'nullable|string',
            'sequence' => 'required|array',
            'sequence.*.event_type' => 'required|string',
            'sequence.*.time_delta' => 'required|integer|min=0|max=3600',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $pattern = $this->correlationService->createPattern($request->all());

        return response()->json([
            'success' => true,
            'data' => $pattern,
            'message' => 'Tạo correlation pattern thành công'
        ]);
    }

    /**
     * Incident timeline
     */
    public function incidentTimeline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'incident_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $timeline = $this->correlationService->getIncidentTimeline($request->incident_id);

        return response()->json([
            'success' => true,
            'data' => $timeline
        ]);
    }

    /**
     * Attack chain visualization
     */
    public function attackChain(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'events' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $chain = $this->correlationService->buildAttackChain($request->events);

        return response()->json([
            'success' => true,
            'data' => $chain
        ]);
    }
}