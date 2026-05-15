<?php

namespace App\Http\Controllers\Api\V1\Module24_DevSecOps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SecretScanService;

class SecretScanController extends Controller
{
    protected $secretService;

    public function __construct(SecretScanService $secretService)
    {
        $this->secretService = $secretService;
    }

    /**
     * Quét repository
     */
    public function scanRepository(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository_url' => 'required|url',
            'branch' => 'nullable|string',
            'scan_history' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $scanId = $this->secretService->scanRepository([
            'repository_url' => $request->repository_url,
            'branch' => $request->branch ?? 'main',
            'scan_history' => $request->scan_history ?? false,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => ['scan_id' => $scanId],
            'message' => 'Bắt đầu quét repository'
        ]);
    }

    /**
     * Quét file
     */
    public function scanFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->secretService->scanFile($request->file('file'));

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Findings
     */
    public function findings(Request $request)
    {
        $findings = $this->secretService->getFindings([
            'scan_id' => $request->scan_id,
            'severity' => $request->severity,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $findings
        ]);
    }

    /**
     * Fix finding
     */
    public function fixFinding(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:revoke,remove,ignore',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->secretService->fixFinding($id, $request->action);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Finding fixed' : 'Fix failed'
        ]);
    }

    /**
     * Pre-commit scan
     */
    public function preCommitScan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'filename' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->secretService->preCommitScan(
            $request->content,
            $request->filename
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}