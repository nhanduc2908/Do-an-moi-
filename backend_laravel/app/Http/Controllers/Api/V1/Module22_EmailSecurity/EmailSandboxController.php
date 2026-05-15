<?php

namespace App\Http\Controllers\Api\V1\Module22_EmailSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\EmailSandboxService;

class EmailSandboxController extends Controller
{
    protected $sandboxService;

    public function __construct(EmailSandboxService $sandboxService)
    {
        $this->sandboxService = $sandboxService;
    }

    /**
     * Analyze email
     */
    public function analyzeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_content' => 'required|string',
            'sender' => 'required|email',
            'recipients' => 'required|array',
            'attachments' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysisId = $this->sandboxService->analyze($request->all());

        return response()->json([
            'success' => true,
            'data' => ['analysis_id' => $analysisId],
            'message' => 'Đang phân tích email trong môi trường sandbox'
        ]);
    }

    /**
     * Analysis result
     */
    public function analysisResult($analysisId)
    {
        $result = $this->sandboxService->getResult($analysisId);

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
            'file' => 'required|file|max:20480', // 20MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysis = $this->sandboxService->analyzeAttachment($request->file('file'));

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Detected threats
     */
    public function detectedThreats(Request $request)
    {
        $threats = $this->sandboxService->getDetectedThreats([
            'severity' => $request->severity,
            'start_date' => $request->start_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $threats
        ]);
    }

    /**
     * Sandbox status
     */
    public function sandboxStatus()
    {
        $status = $this->sandboxService->getStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }
}