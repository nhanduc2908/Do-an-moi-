<?php

namespace App\Http\Controllers\Api\V1\Module04_PasswordSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PasswordLeakService;

class PasswordLeakController extends Controller
{
    protected $leakService;

    public function __construct(PasswordLeakService $leakService)
    {
        $this->leakService = $leakService;
    }

    /**
     * Kiểm tra mật khẩu có bị rò rỉ không
     */
    public function checkLeak(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->leakService->checkPassword($request->password);

        return response()->json([
            'success' => true,
            'data' => [
                'is_leaked' => $result['leaked'],
                'leak_count' => $result['count'],
                'sources' => $result['sources'],
                'first_leaked' => $result['first_leaked'] ?? null,
            ]
        ]);
    }

    /**
     * Kiểm tra email có bị rò rỉ không
     */
    public function checkEmailLeak(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->leakService->checkEmail($request->email);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Quét tất cả user passwords
     */
    public function scanAllUsers(Request $request)
    {
        $this->authorize('admin');

        $result = $this->leakService->scanAllUserPasswords();

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Đã quét ' . $result['total_scanned'] . ' users'
        ]);
    }

    /**
     * Thông báo cho user bị rò rỉ mật khẩu
     */
    public function notifyLeakedUsers(Request $request)
    {
        $this->authorize('admin');

        $result = $this->leakService->notifyLeakedUsers();

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Đã gửi thông báo cho ' . $result['notified'] . ' users'
        ]);
    }
}