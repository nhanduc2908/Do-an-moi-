<?php

namespace App\Http\Controllers\Api\V1\Module06_ApiSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ApiKey;
use App\Services\ApiAuthService;

class ApiAuthController extends Controller
{
    protected $apiAuthService;

    public function __construct(ApiAuthService $apiAuthService)
    {
        $this->apiAuthService = $apiAuthService;
    }

    /**
     * Đăng ký API key mới
     */
    public function registerKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'allowed_ips' => 'nullable|array',
            'allowed_ips.*' => 'ip',
            'rate_limit' => 'nullable|integer|min=10|max=10000',
            'expires_in_days' => 'nullable|integer|min=1|max=365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $apiKey = $this->apiAuthService->createApiKey(
            auth()->id(),
            $request->name,
            [
                'allowed_ips' => $request->allowed_ips,
                'rate_limit' => $request->rate_limit ?? 1000,
                'expires_in_days' => $request->expires_in_days ?? 30,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'api_key' => $apiKey['key'],
                'api_secret' => $apiKey['secret'],
                'expires_at' => $apiKey['expires_at'],
            ],
            'message' => 'API key đã được tạo (lưu lại api_secret, chỉ hiển thị một lần)'
        ]);
    }

    /**
     * Danh sách API keys
     */
    public function listKeys(Request $request)
    {
        $keys = ApiKey::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $keys->map(function($key) {
                return [
                    'id' => $key->id,
                    'name' => $key->name,
                    'last_used_at' => $key->last_used_at,
                    'created_at' => $key->created_at,
                    'expires_at' => $key->expires_at,
                    'is_active' => $key->is_active,
                ];
            })
        ]);
    }

    /**
     * Revoke API key
     */
    public function revokeKey($id)
    {
        $key = ApiKey::where('user_id', auth()->id())->findOrFail($id);
        $key->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'API key đã được thu hồi'
        ]);
    }

    /**
     * Xóa API key
     */
    public function deleteKey($id)
    {
        $key = ApiKey::where('user_id', auth()->id())->findOrFail($id);
        $key->delete();

        return response()->json([
            'success' => true,
            'message' => 'API key đã được xóa'
        ]);
    }
}