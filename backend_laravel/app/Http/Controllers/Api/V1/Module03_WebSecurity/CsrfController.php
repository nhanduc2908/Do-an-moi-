<?php

namespace App\Http\Controllers\Api\V1\Module03_WebSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CsrfProtectionService;

class CsrfController extends Controller
{
    protected $csrfService;

    public function __construct(CsrfProtectionService $csrfService)
    {
        $this->csrfService = $csrfService;
    }

    /**
     * Lấy CSRF token
     */
    public function token(Request $request)
    {
        $token = $this->csrfService->generateToken();

        return response()->json([
            'success' => true,
            'data' => [
                'csrf_token' => $token,
                'expires_in' => config('session.lifetime') * 60,
            ]
        ]);
    }

    /**
     * Kiểm tra CSRF token
     */
    public function verify(Request $request)
    {
        $token = $request->header('X-CSRF-TOKEN') ?? $request->input('_token');

        $isValid = $this->csrfService->verifyToken($token);

        return response()->json([
            'success' => $isValid,
            'message' => $isValid ? 'Token hợp lệ' : 'Token không hợp lệ'
        ]);
    }

    /**
     * Refresh token
     */
    public function refresh(Request $request)
    {
        $newToken = $this->csrfService->refreshToken();

        return response()->json([
            'success' => true,
            'data' => [
                'csrf_token' => $newToken,
            ]
        ]);
    }
}