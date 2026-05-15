<?php

namespace App\Http\Controllers\Api\V1\Module11_CloudSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CloudIamService;

class CloudIamController extends Controller
{
    protected $iamService;

    public function __construct(CloudIamService $iamService)
    {
        $this->iamService = $iamService;
    }

    /**
     * Danh sách IAM users
     */
    public function users(Request $request)
    {
        $users = $this->iamService->getUsers($request->provider ?? 'aws');

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Danh sách roles
     */
    public function roles(Request $request)
    {
        $roles = $this->iamService->getRoles($request->provider ?? 'aws');

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * Phân tích permissions
     */
    public function analyzePermissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'provider' => 'required|in:aws,azure,gcp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysis = $this->iamService->analyzePermissions(
            $request->user_id,
            $request->provider
        );

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Phát hiện privilege escalation
     */
    public function privilegeEscalation(Request $request)
    {
        $risks = $this->iamService->detectPrivilegeEscalation();

        return response()->json([
            'success' => true,
            'data' => $risks
        ]);
    }

    /**
     * Gợi ý least privilege
     */
    public function leastPrivilegeSuggestions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'provider' => 'required|in:aws,azure,gcp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $suggestions = $this->iamService->getLeastPrivilegeSuggestions(
            $request->user_id,
            $request->provider
        );

        return response()->json([
            'success' => true,
            'data' => $suggestions
        ]);
    }

    /**
     * Service accounts
     */
    public function serviceAccounts(Request $request)
    {
        $accounts = $this->iamService->getServiceAccounts($request->provider ?? 'aws');

        return response()->json([
            'success' => true,
            'data' => $accounts
        ]);
    }

    /**
     * Access keys
     */
    public function accessKeys(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'provider' => 'required|in:aws,azure,gcp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $keys = $this->iamService->getAccessKeys(
            $request->user_id,
            $request->provider
        );

        return response()->json([
            'success' => true,
            'data' => $keys
        ]);
    }

    /**
     * Revoke access key
     */
    public function revokeAccessKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key_id' => 'required|string',
            'provider' => 'required|in:aws,azure,gcp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->iamService->revokeAccessKey(
            $request->key_id,
            $request->provider
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Access key đã được thu hồi' : 'Thu hồi thất bại'
        ]);
    }
}