<?php

namespace App\Http\Controllers\Api\V1\Module06_ApiSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RevokedToken;
use App\Services\TokenRevocationService;

class TokenRevocationController extends Controller
{
    protected $revocationService;

    public function __construct(TokenRevocationService $revocationService)
    {
        $this->revocationService = $revocationService;
    }

    /**
     * Thu hồi token
     */
    public function revoke(Request $request)
    {
        $token = $request->bearerToken();
        
        $this->revocationService->revokeToken($token, 'user_initiated');

        return response()->json([
            'success' => true,
            'message' => 'Token đã được thu hồi'
        ]);
    }

    /**
     * Thu hồi tất cả token của user
     */
    public function revokeAllUserTokens(Request $request)
    {
        $userId = $request->user_id ?? auth()->id();
        
        $this->revocationService->revokeAllUserTokens($userId, 'user_initiated');

        return response()->json([
            'success' => true,
            'message' => 'Tất cả token đã được thu hồi'
        ]);
    }

    /**
     * Danh sách token đã thu hồi
     */
    public function listRevoked(Request $request)
    {
        $tokens = RevokedToken::when($request->user_id, function($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('revoked_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $tokens
        ]);
    }

    /**
     * Kiểm tra token
     */
    public function check(Request $request)
    {
        $token = $request->token ?? $request->bearerToken();
        
        $isRevoked = $this->revocationService->isTokenRevoked($token);

        return response()->json([
            'success' => true,
            'data' => [
                'is_revoked' => $isRevoked
            ]
        ]);
    }

    /**
     * Dọn dẹp token hết hạn
     */
    public function cleanup(Request $request)
    {
        $deleted = RevokedToken::where('expires_at', '<', now())->delete();

        return response()->json([
            'success' => true,
            'data' => ['cleaned_count' => $deleted],
            'message' => "Đã dọn dẹp {$deleted} token hết hạn"
        ]);
    }
}