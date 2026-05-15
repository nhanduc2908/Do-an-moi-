<?php

namespace App\Http\Controllers\Api\V1\Module26_AIEngine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AI\AutoEvaluationService;

class AutoEvaluationController extends Controller
{
    protected $evalService;

    public function __construct(AutoEvaluationService $evalService)
    {
        $this->evalService = $evalService;
    }

    /**
     * Evaluate assessment
     */
    public function evaluateAssessment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assessment_id' => 'required|string',
            'criteria' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->evalService->evaluate($request->assessment_id, $request->criteria);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Auto score
     */
    public function autoScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|string',
            'criteria' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $score = $this->evalService->calculateScore($request->data, $request->criteria);

        return response()->json([
            'success' => true,
            'data' => $score
        ]);
    }

    /**
     * Generate feedback
     */
    public function generateFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluation_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $feedback = $this->evalService->generateFeedback($request->evaluation_id);

        return response()->json([
            'success' => true,
            'data' => $feedback
        ]);
    }

    /**
     * Evaluation report
     */
    public function evaluationReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluation_id' => 'required|string',
            'format' => 'nullable|in:pdf,json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->evalService->generateReport(
            $request->evaluation_id,
            $request->format ?? 'json'
        );

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}