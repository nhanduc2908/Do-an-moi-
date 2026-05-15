<?php

namespace App\Http\Controllers\Api\V1\Module26_AIEngine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AI\PhishingAIService;

class PhishingAIController extends Controller
{
    protected $phishingService;

    public function __construct(PhishingAIService $phishingService)
    {
        $this->phishingService = $phishingService;
    }

    /**
     * Analyze email
     */
    public function analyzeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_content' => 'required|string',
            'headers' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->phishingService->analyzeEmail($request->all());

        return response()->json([
            'success' => true,
            'data' => [
                'is_phishing' => $result['phishing'],
                'confidence_score' => $result['confidence'],
                'indicators' => $result['indicators'],
                'risk_level' => $result['risk_level'],
            ]
        ]);
    }

    /**
     * Analyze URL
     */
    public function analyzeUrl(Request $request)
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

        $result = $this->phishingService->analyzeUrl($request->url);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Analyze attachment
     */
    public function analyzeAttachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->phishingService->analyzeAttachment($request->file('file'));

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Confidence score
     */
    public function confidenceScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'analysis_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $score = $this->phishingService->getConfidenceScore($request->analysis_id);

        return response()->json([
            'success' => true,
            'data' => $score
        ]);
    }
}