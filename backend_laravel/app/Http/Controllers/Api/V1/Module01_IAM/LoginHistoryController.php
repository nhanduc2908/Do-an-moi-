<?php

namespace App\Http\Controllers\Api\V1\Module01_IAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoginHistory;

class LoginHistoryController extends Controller
{
    /**
     * Lịch sử đăng nhập
     */
    public function index(Request $request)
    {
        $history = LoginHistory::where('user_id', $request->user()->id)
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('login_at', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                $query->whereDate('login_at', '<=', $date);
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('login_at', 'desc')
            ->paginate($request->per_page ?? 30);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Thống kê đăng nhập
     */
    public function statistics(Request $request)
    {
        $stats = [
            'total_logins' => LoginHistory::where('user_id', $request->user()->id)->count(),
            'successful_logins' => LoginHistory::where('user_id', $request->user()->id)
                ->where('status', 'success')->count(),
            'failed_logins' => LoginHistory::where('user_id', $request->user()->id)
                ->where('status', 'failed')->count(),
            'logins_last_7_days' => LoginHistory::where('user_id', $request->user()->id)
                ->where('login_at', '>=', now()->subDays(7))->count(),
            'unique_ips' => LoginHistory::where('user_id', $request->user()->id)
                ->distinct('ip_address')->count('ip_address'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Cảnh báo đăng nhập lạ
     */
    public function unusualActivity(Request $request)
    {
        $unusual = LoginHistory::where('user_id', $request->user()->id)
            ->where('is_unusual', true)
            ->orderBy('login_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $unusual
        ]);
    }
}