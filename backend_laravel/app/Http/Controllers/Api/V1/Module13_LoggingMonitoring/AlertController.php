<?php

namespace App\Http\Controllers\Api\V1\Module13_LoggingMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Alert;
use App\Services\AlertService;

class AlertController extends Controller
{
    protected $alertService;

    public function __construct(AlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    /**
     * Danh sách alerts
     */
    public function index(Request $request)
    {
        $alerts = Alert::with('user')
            ->when($request->severity, function($query, $severity) {
                $query->where('severity', $severity);
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->source, function($query, $source) {
                $query->where('source', $source);
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
            'data' => $alerts
        ]);
    }

    /**
     * Chi tiết alert
     */
    public function show($id)
    {
        $alert = Alert::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $alert
        ]);
    }

    /**
     * Xác nhận alert
     */
    public function acknowledge($id)
    {
        $alert = Alert::findOrFail($id);
        $alert->update([
            'status' => 'acknowledged',
            'acknowledged_by' => auth()->id(),
            'acknowledged_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alert đã được xác nhận'
        ]);
    }

    /**
     * Đóng alert
     */
    public function resolve($id)
    {
        $validator = Validator::make($request->all(), [
            'resolution_note' => 'nullable|string',
        ]);

        $alert = Alert::findOrFail($id);
        $alert->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'resolution_note' => $request->resolution_note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alert đã được đóng'
        ]);
    }

    /**
     * Tạo alert rule
     */
    public function createRule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'condition' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'channels' => 'required|array',
            'channels.*' => 'in:email,sms,webhook,push',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $rule = $this->alertService->createRule($request->all());

        return response()->json([
            'success' => true,
            'data' => $rule,
            'message' => 'Tạo alert rule thành công'
        ]);
    }

    /**
     * Danh sách alert rules
     */
    public function rules(Request $request)
    {
        $rules = $this->alertService->getRules();

        return response()->json([
            'success' => true,
            'data' => $rules
        ]);
    }

    /**
     * Thống kê alerts
     */
    public function statistics(Request $request)
    {
        $stats = Alert::selectRaw('severity, status, count(*) as count')
            ->groupBy('severity', 'status')
            ->get();

        $summary = [
            'total' => Alert::count(),
            'by_severity' => [
                'critical' => Alert::where('severity', 'critical')->count(),
                'high' => Alert::where('severity', 'high')->count(),
                'medium' => Alert::where('severity', 'medium')->count(),
                'low' => Alert::where('severity', 'low')->count(),
            ],
            'by_status' => [
                'new' => Alert::where('status', 'new')->count(),
                'acknowledged' => Alert::where('status', 'acknowledged')->count(),
                'resolved' => Alert::where('status', 'resolved')->count(),
            ],
            'last_24h' => Alert::where('created_at', '>=', now()->subDay())->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Export alerts
     */
    public function export(Request $request)
    {
        $format = $request->format ?? 'csv';
        $alerts = $this->alertService->exportAlerts($request->all(), $format);

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * Gửi test alert
     */
    public function sendTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'required|in:email,sms,webhook',
            'recipient' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->alertService->sendTestAlert(
            $request->channel,
            $request->recipient
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Test alert đã được gửi' : 'Gửi test alert thất bại'
        ]);
    }
}