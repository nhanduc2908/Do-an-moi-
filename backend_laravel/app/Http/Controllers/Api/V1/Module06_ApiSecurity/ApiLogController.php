<?php

namespace App\Http\Controllers\Api\V1\Module06_ApiSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiLog;

class ApiLogController extends Controller
{
    /**
     * Danh sách API logs
     */
    public function index(Request $request)
    {
        $logs = ApiLog::with('user')
            ->when($request->user_id, function($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when($request->method, function($query, $method) {
                $query->where('method', $method);
            })
            ->when($request->status_code, function($query, $status) {
                $query->where('status_code', $status);
            })
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 50);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Chi tiết log
     */
    public function show($id)
    {
        $log = ApiLog::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $log
        ]);
    }

    /**
     * Thống kê API logs
     */
    public function statistics(Request $request)
    {
        $stats = [
            'total_requests' => ApiLog::count(),
            'total_errors' => ApiLog::where('status_code', '>=', 400)->count(),
            'avg_response_time' => ApiLog::avg('response_time'),
            'requests_by_method' => ApiLog::selectRaw('method, count(*) as count')
                ->groupBy('method')
                ->get(),
            'requests_by_status' => ApiLog::selectRaw('status_code, count(*) as count')
                ->groupBy('status_code')
                ->get(),
            'requests_last_hour' => ApiLog::where('created_at', '>=', now()->subHour())->count(),
            'requests_today' => ApiLog::whereDate('created_at', today())->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Xóa logs cũ
     */
    public function cleanOld(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer|min=1|max=365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $deleted = ApiLog::where('created_at', '<', now()->subDays($request->days))->delete();

        return response()->json([
            'success' => true,
            'data' => ['deleted_count' => $deleted],
            'message' => "Đã xóa {$deleted} logs cũ"
        ]);
    }

    /**
     * Export logs
     */
    public function export(Request $request)
    {
        $format = $request->format ?? 'json';
        $logs = ApiLog::when($request->start_date, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->get();

        $filename = 'api_logs_' . now()->format('Ymd_His') . '.' . $format;

        if ($format === 'csv') {
            $csv = $this->arrayToCsv($logs->toArray());
            return response($csv, 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        }

        return response()->json($logs);
    }

    private function arrayToCsv(array $data)
    {
        if (empty($data)) return '';
        
        $output = fopen('php://temp', 'r+');
        fputcsv($output, array_keys($data[0]));
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        return stream_get_contents($output);
    }
}