<?php

namespace App\Http\Controllers\Api\V1\Module01_IAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Session;

class SessionController extends Controller
{
    /**
     * Danh sách sessions của user hiện tại
     */
    public function index(Request $request)
    {
        $sessions = Session::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    /**
     * Chi tiết session
     */
    public function show($id)
    {
        $session = Session::where('user_id', auth()->id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $session
        ]);
    }

    /**
     * Hủy session (đăng xuất từ xa)
     */
    public function destroy(Request $request, $id)
    {
        $session = Session::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        // Revoke token
        $token = $request->user()->tokens()
            ->where('id', $session->token_id)
            ->first();

        if ($token) {
            $token->delete();
        }

        $session->update(['logged_out_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy session'
        ]);
    }

    /**
     * Hủy tất cả sessions ngoại trừ session hiện tại
     */
    public function destroyOthers(Request $request)
    {
        $currentToken = $request->user()->currentAccessToken();
        
        $sessions = Session::where('user_id', $request->user()->id)
            ->where('token_id', '!=', $currentToken->id)
            ->get();

        foreach ($sessions as $session) {
            $token = $request->user()->tokens()
                ->where('id', $session->token_id)
                ->first();
            if ($token) {
                $token->delete();
            }
            $session->update(['logged_out_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy ' . $sessions->count() . ' sessions khác'
        ]);
    }
}