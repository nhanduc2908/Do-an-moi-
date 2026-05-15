<?php

namespace App\Http\Controllers\Api\V1\Module01_IAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Session;
use App\Events\UserLoggedInEvent;
use App\Events\UserLoggedOutEvent;

class AuthController extends Controller
{
    /**
     * Đăng nhập
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'device_name' => 'nullable|string',
            'ip_address' => 'nullable|ip',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }

        if ($user->is_locked) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã bị khóa'
            ], 403);
        }

        // Tạo token
        $token = $user->createToken($request->device_name ?? 'web')->plainTextToken;

        // Ghi nhận session
        $session = Session::create([
            'user_id' => $user->id,
            'token' => $token,
            'ip_address' => $request->ip_address ?? $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_name' => $request->device_name,
            'expires_at' => now()->addDays(7),
        ]);

        // Fire event
        event(new UserLoggedInEvent($user->id, $user->email, $session->ip_address, $request->userAgent()));

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user->only(['id', 'name', 'email', 'role_id']),
                'token' => $token,
                'session_id' => $session->id,
                'expires_at' => $session->expires_at,
            ]
        ]);
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $session = Session::where('token', $request->bearerToken())->first();

        // Revoke token
        $request->user()->currentAccessToken()->delete();

        if ($session) {
            $session->update(['logged_out_at' => now()]);
            event(new UserLoggedOutEvent($user->id, $user->email, $session->id, 'user_initiated'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    /**
     * Đăng xuất tất cả các thiết bị
     */
    public function logoutAllDevices(Request $request)
    {
        $user = $request->user();
        $tokens = $user->tokens;
        
        foreach ($tokens as $token) {
            $token->delete();
        }

        Session::where('user_id', $user->id)->update(['logged_out_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Đã đăng xuất tất cả thiết bị'
        ]);
    }

    /**
     * Lấy thông tin user hiện tại
     */
    public function me(Request $request)
    {
        $user = $request->user()->load('role');

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không đúng'
            ], 401);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đổi mật khẩu thành công'
        ]);
    }
}