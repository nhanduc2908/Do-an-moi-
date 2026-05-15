<?php

namespace App\Http\Controllers\Api\V1\Module27_ReportAnalytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SecurityScoreService;

class SecurityScoreController extends Controller
{
    protected $scoreService;

    public function __construct(SecurityScoreService $scoreService)
    {
        $this->scoreService = $scoreService;
    }

    /**
     * Overall score
     */
    public function overallScore()
    {
        $score = $this->scoreService->getOverallScore();

        return response()->json([
            'success' => true,
            'data' => $score
        ]);
    }

    /**
     * Category scores
     */
    public function categoryScores()
    {
        $scores = $this->scoreService->getCategoryScores();

        return response()->json([
            'success' => true,
            'data' => $scores
        ]);
    }

    /**
     * Score trend
     */
    public function scoreTrend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'nullable|integer|min=7|max=365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $trend = $this->scoreService->getScoreTrend($request->days ?? 30);

        return response()->json([
            'success' => true,
            'data' => $trend
        ]);
    }

    /**
     * Benchmark
     */
    public function benchmark()
    {
        $benchmark = $this->scoreService->getBenchmark();

        return response()->json([
            'success' => true,
            'data' => $benchmark
        ]);
    }

    /**
     * Score history
     */
    public function scoreHistory(Request $request)
    {
        $history = $this->scoreService->getScoreHistory([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}