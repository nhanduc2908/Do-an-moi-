<?php

namespace App\Http\Controllers\Api\V1\Module07_SourceCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SecretDetectionService;

class SecretDetectionController extends Controller
{
    protected $secretService;

    public function __construct(SecretDetectionService $secretService)
    {
        $this->secretService = $secretService;
    }

    /**
     * Quét tìm secret
     */
    public function scan(Request $request)
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

        $scanId = $this->secretService->startScan([
            'repository_url' => $request->repository_url,
            'branch' => $request->branch ?? 'main',
            'scan_history' => $request->scan_history ?? false,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'scan_id' => $scanId,
                'status' => 'started',
            ],
            'message' => 'Bắt đầu quét tìm secret'
        ]);
    }

    /**
     * Kết quả quét
     */
    public function result($scanId)
    {
        $result = $this->secretService->getResult($scanId);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Kiểm tra text có chứa secret không
     */
    public function checkText(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $secrets = $this->secretService->detectSecrets($request->content);

        return response()->json([
            'success' => true,
            'data' => [
                'has_secrets' => !empty($secrets),
                'secrets_found' => $secrets,
            ]
        ]);
    }

    /**
     * Revoke secret đã bị lộ
     */
    public function revokeSecret(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret_type' => 'required|string',
            'secret_value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->secretService->revokeSecret(
            $request->secret_type,
            $request->secret_value
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Secret đã được revoke' : 'Không thể revoke secret'
        ]);
    }
}