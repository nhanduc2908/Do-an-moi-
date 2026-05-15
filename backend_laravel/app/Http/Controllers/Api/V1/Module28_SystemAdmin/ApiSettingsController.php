<?php

namespace App\Http\Controllers\Api\V1\Module28_SystemAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ApiSettingsService;

class ApiSettingsController extends Controller
{
    protected $apiService;

    public function __construct(ApiSettingsService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * API keys
     */
    public function apiKeys(Request $request)
    {
        $keys = $this->apiService->getApiKeys([
            'user_id' => $request->user_id,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $keys
        ]);
    }

    /**
     * Generate key
     */
    public function generateKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'permissions' => 'nullable|array',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $key = $this->apiService->generateKey($request->all());

        return response()->json([
            'success' => true,
            'data' => $key,
            'message' => 'API key generated'
        ]);
    }

    /**
     * Revoke key
     */
    public function revokeKey($id)
    {
        $result = $this->apiService->revokeKey($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'API key revoked' : 'Revoke failed'
        ]);
    }

    /**
     * Rate limits
     */
    public function rateLimits()
    {
        $limits = $this->apiService->getRateLimits();

        return response()->json([
            'success' => true,
            'data' => $limits
        ]);
    }

    /**
     * Update rate limits
     */
    public function updateRateLimits(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'default' => 'nullable|integer|min:10|max:10000',
            'authenticated' => 'nullable|integer|min:10|max:10000',
            'admin' => 'nullable|integer|min:10|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $limits = $this->apiService->updateRateLimits($request->all());

        return response()->json([
            'success' => true,
            'data' => $limits,
            'message' => 'Rate limits updated'
        ]);
    }

    /**
     * API logs
     */
    public function apiLogs(Request $request)
    {
        $logs = $this->apiService->getApiLogs([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status_code' => $request->status_code,
        ]);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Endpoints
     */
    public function endpoints()
    {
        $endpoints = $this->apiService->getEndpoints();

        return response()->json([
            'success' => true,
            'data' => $endpoints
        ]);
    }

    /**
     * Generate documentation
     */
    public function generateDocs()
    {
        $docs = $this->apiService->generateDocumentation();

        return response()->json([
            'success' => true,
            'data' => $docs
        ]);
    }
}