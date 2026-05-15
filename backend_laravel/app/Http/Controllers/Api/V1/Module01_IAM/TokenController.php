<?php

namespace App\Http\Controllers\Api\V1\Module01_IAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TokenController extends Controller
{
    /**
     * Danh sách API tokens
     */
    public function index(Request $request)
    {
        $tokens = $request->user()->tokens()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'abilities' => $token->abilities,
                    'last_used_at' => $token->last_used_at,
                    'created_at' => $token->created_at,
                    'expires_at' => $token->expires_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $tokens
        ]);
    }

    /**
     * Tạo token mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'abilities' => 'array',
            'expires_in_days' => 'nullable|integer|min=1|max=365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $expiresAt = $request->expires_in_days 
            ? now()->addDays($request->expires_in_days)
            : now()->addDays(30);

        $token = $request->user()->createToken(
            $request->name,
            $request->abilities ?? ['*'],
            $expiresAt
        );

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token->plainTextToken,
                'expires_at' => $expiresAt,
            ],
            'message' => 'Tạo token thành công'
        ], 201);
    }

    /**
     * Revoke token
     */
    public function destroy(Request $request, $id)
    {
        $token = $request->user()->tokens()->findOrFail($id);
        $token->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token đã được thu hồi'
        ]);
    }

    /**
     * Revoke tất cả tokens
     */
    public function destroyAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tất cả tokens đã được thu hồi'
        ]);
    }
}