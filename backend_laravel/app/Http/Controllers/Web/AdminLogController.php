<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\ApiLog;
use App\Models\LoginHistory;

class AdminLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Audit logs
     */
    public function audit(Request $request)
    {
        $logs = AuditLog::with('user')
            ->when($request->search, function($query, $search) {
                $query->where('event', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->user_id, function($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when($request->event, function($query, $event) {
                $query->where('event', $event);
            })
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $events = AuditLog::distinct()->pluck('event');
        $users = \App\Models\User::select('id', 'name')->get();

        return view('admin.logs.audit', compact('logs', 'events', 'users'));
    }

    /**
     * API logs
     */
    public function api(Request $request)
    {
        $logs = ApiLog::with('user')
            ->when($request->search, function($query, $search) {
                $query->where('method', 'like', "%{$search}%")
                      ->orWhere('path', 'like', "%{$search}%")
                      ->orWhere('ip_address', 'like', "%{$search}%");
            })
            ->when($request->method, function($query, $method) {
                $query->where('method', $method);
            })
            ->when($request->status_code, function($query, $statusCode) {
                $query->where('status_code', $statusCode);
            })
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $methods = ApiLog::distinct()->pluck('method');
        $statusCodes = ApiLog::distinct()->pluck('status_code');

        return view('admin.logs.api', compact('logs', 'methods', 'statusCodes'));
    }

    /**
     * Login history
     */
    public function loginHistory(Request $request)
    {
        $logs = LoginHistory::with('user')
            ->when($request->search, function($query, $search) {
                $query->where('email', 'like', "%{$search}%")
                      ->orWhere('ip_address', 'like', "%{$search}%");
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('login_at', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                $query->whereDate('login_at', '<=', $date);
            })
            ->orderBy('login_at', 'desc')
            ->paginate(50);

        return view('admin.logs.login', compact('logs'));
    }

    /**
     * System logs
     */
    public function system(Request $request)
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            $lines = explode("\n", $content);
            $logs = array_reverse(array_filter($lines));
            
            if ($request->search) {
                $logs = array_filter($logs, function($line) use ($request) {
                    return stripos($line, $request->search) !== false;
                });
            }
            
            $logs = array_slice($logs, 0, $request->limit ?? 500);
        }

        return view('admin.logs.system', compact('logs'));
    }

    /**
     * Clear system logs
     */
    public function clearSystem()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }

        return back()->with('success', 'System logs cleared successfully.');
    }

    /**
     * Export logs
     */
    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:audit,api,login',
            'format' => 'required|in:csv,json',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $logs = $this->getLogsForExport($request->all());
        
        if ($request->format === 'csv') {
            return $this->exportCsv($logs, $request->type);
        }

        return response()->json($logs);
    }

    private function getLogsForExport($params)
    {
        $query = match($params['type']) {
            'audit' => AuditLog::with('user'),
            'api' => ApiLog::with('user'),
            'login' => LoginHistory::with('user'),
            default => collect(),
        };

        if (isset($params['start_date'])) {
            $query->whereDate('created_at', '>=', $params['start_date']);
        }
        if (isset($params['end_date'])) {
            $query->whereDate('created_at', '<=', $params['end_date']);
        }

        return $query->get();
    }

    private function exportCsv($logs, $type)
    {
        $filename = "{$type}_logs_" . date('Ymd_His') . ".csv";
        $handle = fopen('php://temp', 'w');

        if (count($logs) > 0) {
            fputcsv($handle, array_keys($logs->first()->toArray()));
        }

        foreach ($logs as $log) {
            fputcsv($handle, $log->toArray());
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    /**
     * Log details
     */
    public function show($type, $id)
    {
        $log = match($type) {
            'audit' => AuditLog::with('user')->findOrFail($id),
            'api' => ApiLog::with('user')->findOrFail($id),
            'login' => LoginHistory::with('user')->findOrFail($id),
            default => abort(404),
        };

        return view('admin.logs.show', compact('log', 'type'));
    }
}