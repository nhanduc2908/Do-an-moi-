<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Models\User;
use App\Models\Incident;
use App\Models\Risk;
use App\Models\AuditLog;

class AdminDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Dashboard chính
     */
    public function index()
    {
        $data = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_incidents' => Incident::count(),
            'critical_incidents' => Incident::where('severity', 'critical')->count(),
            'total_risks' => Risk::count(),
            'high_risks' => Risk::where('level', 'high')->count(),
            'recent_activities' => AuditLog::latest()->limit(10)->get(),
            'security_score' => $this->dashboardService->getSecurityScore(),
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Widget data cho AJAX
     */
    public function widgets(Request $request)
    {
        $widget = $request->widget;
        
        $data = match($widget) {
            'users' => $this->getUserStats(),
            'incidents' => $this->getIncidentStats(),
            'risks' => $this->getRiskStats(),
            'performance' => $this->getPerformanceStats(),
            default => []
        };

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Thống kê realtime
     */
    public function realtimeStats()
    {
        $stats = [
            'active_sessions' => \DB::table('sessions')->count(),
            'failed_logins_today' => \DB::table('login_history')
                ->whereDate('created_at', today())
                ->where('status', 'failed')
                ->count(),
            'api_requests_today' => \DB::table('api_logs')
                ->whereDate('created_at', today())
                ->count(),
            'backup_status' => $this->getBackupStatus(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    private function getUserStats()
    {
        return [
            'total' => User::count(),
            'new_today' => User::whereDate('created_at', today())->count(),
            'active' => User::where('is_active', true)->count(),
            'locked' => User::where('is_locked', true)->count(),
            'by_role' => User::selectRaw('role_id, count(*) as count')
                ->groupBy('role_id')
                ->get(),
        ];
    }

    private function getIncidentStats()
    {
        return [
            'total' => Incident::count(),
            'critical' => Incident::where('severity', 'critical')->count(),
            'high' => Incident::where('severity', 'high')->count(),
            'resolved' => Incident::where('status', 'resolved')->count(),
            'by_type' => Incident::selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->get(),
        ];
    }

    private function getRiskStats()
    {
        return [
            'total' => Risk::count(),
            'critical' => Risk::where('level', 'critical')->count(),
            'high' => Risk::where('level', 'high')->count(),
            'mitigated' => Risk::where('status', 'mitigated')->count(),
        ];
    }

    private function getPerformanceStats()
    {
        return [
            'avg_response_time' => \DB::table('api_logs')->avg('response_time'),
            'success_rate' => $this->calculateSuccessRate(),
            'uptime' => $this->getUptime(),
        ];
    }

    private function calculateSuccessRate()
    {
        $total = \DB::table('api_logs')->count();
        $success = \DB::table('api_logs')->where('status_code', 200)->count();
        
        return $total > 0 ? round(($success / $total) * 100, 2) : 100;
    }

    private function getUptime()
    {
        // Giả lập uptime
        return '99.99%';
    }

    private function getBackupStatus()
    {
        $lastBackup = \DB::table('backup_history')
            ->orderBy('created_at', 'desc')
            ->first();

        return [
            'last_backup' => $lastBackup?->created_at,
            'status' => $lastBackup?->status ?? 'unknown',
        ];
    }
}