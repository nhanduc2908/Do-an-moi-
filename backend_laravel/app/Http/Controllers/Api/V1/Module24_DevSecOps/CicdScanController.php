<?php

namespace App\Http\Controllers\Api\V1\Module24_DevSecOps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CicdScanService;

class CicdScanController extends Controller
{
    protected $cicdService;

    public function __construct(CicdScanService $cicdService)
    {
        $this->cicdService = $cicdService;
    }

    /**
     * Quét pipeline
     */
    public function scanPipeline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository' => 'required|string',
            'branch' => 'nullable|string',
            'pipeline_type' => 'required|in:github_actions,gitlab_ci,jenkins,circleci',
            'scan_depth' => 'nullable|in:quick,full',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $scanId = $this->cicdService->startScan([
            'repository' => $request->repository,
            'branch' => $request->branch ?? 'main',
            'pipeline_type' => $request->pipeline_type,
            'scan_depth' => $request->scan_depth ?? 'quick',
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => ['scan_id' => $scanId],
            'message' => 'Bắt đầu quét CI/CD pipeline'
        ]);
    }

    /**
     * Kết quả quét
     */
    public function scanResult($scanId)
    {
        $result = $this->cicdService->getScanResult($scanId);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Webhook handler
     */
    public function webhookHandler(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event' => 'required|string',
            'repository' => 'required|string',
            'commit_sha' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->cicdService->handleWebhook($request->all());

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Webhook processed'
        ]);
    }

    /**
     * Quality gate
     */
    public function qualityGate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository' => 'required|string',
            'commit_sha' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->cicdService->checkQualityGate(
            $request->repository,
            $request->commit_sha
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Update quality gate
     */
    public function updateQualityGate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rules' => 'required|array',
            'rules.*.condition' => 'required|string',
            'rules.*.threshold' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $rules = $this->cicdService->updateQualityGate($request->rules);

        return response()->json([
            'success' => true,
            'data' => $rules,
            'message' => 'Quality gate updated'
        ]);
    }
}