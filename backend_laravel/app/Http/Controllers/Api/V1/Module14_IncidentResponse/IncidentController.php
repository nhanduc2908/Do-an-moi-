<?php

namespace App\Http\Controllers\Api\V1\Module14_IncidentResponse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Incident;
use App\Services\IncidentService;

class IncidentController extends Controller
{
    protected $incidentService;

    public function __construct(IncidentService $incidentService)
    {
        $this->incidentService = $incidentService;
    }

    /**
     * Danh sách incidents
     */
    public function index(Request $request)
    {
        $incidents = Incident::with('reporter', 'assignee')
            ->when($request->severity, function($query, $severity) {
                $query->where('severity', $severity);
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->type, function($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('occurred_at', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                $query->whereDate('occurred_at', '<=', $date);
            })
            ->orderBy('occurred_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $incidents
        ]);
    }

    /**
     * Chi tiết incident
     */
    public function show($id)
    {
        $incident = Incident::with('reporter', 'assignee', 'timeline', 'evidence')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $incident
        ]);
    }

    /**
     * Tạo incident mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max=200',
            'description' => 'required|string',
            'type' => 'required|in:breach,malware,dos,unauthorized_access,data_loss,other',
            'severity' => 'required|in:low,medium,high,critical',
            'affected_systems' => 'nullable|array',
            'occurred_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $incident = $this->incidentService->createIncident([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'severity' => $request->severity,
            'affected_systems' => $request->affected_systems,
            'occurred_at' => $request->occurred_at ?? now(),
            'reported_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $incident,
            'message' => 'Tạo incident thành công'
        ], 201);
    }

    /**
     * Cập nhật incident
     */
    public function update(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'assignee_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:new,investigating,contained,eradicated,recovered,closed',
            'severity' => 'nullable|in:low,medium,high,critical',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $incident->update($request->only(['assignee_id', 'status', 'severity']));

        return response()->json([
            'success' => true,
            'data' => $incident,
            'message' => 'Cập nhật incident thành công'
        ]);
    }

    /**
     * Thêm timeline
     */
    public function addTimeline(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $timeline = $this->incidentService->addTimelineEntry(
            $id,
            $request->action,
            $request->description,
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'data' => $timeline,
            'message' => 'Thêm timeline thành công'
        ]);
    }

    /**
     * Thêm evidence
     */
    public function addEvidence(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:102400',
            'description' => 'nullable|string',
            'type' => 'nullable|in:log,screenshot,memory_dump,network_capture,other',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evidence = $this->incidentService->addEvidence(
            $id,
            $request->file('file'),
            $request->description,
            $request->type
        );

        return response()->json([
            'success' => true,
            'data' => $evidence,
            'message' => 'Thêm evidence thành công'
        ]);
    }

    /**
     * Đóng incident
     */
    public function close(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'resolution' => 'required|string',
            'lessons_learned' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $incident = $this->incidentService->closeIncident(
            $id,
            $request->resolution,
            $request->lessons_learned
        );

        return response()->json([
            'success' => true,
            'data' => $incident,
            'message' => 'Incident đã được đóng'
        ]);
    }

    /**
     * Thống kê incidents
     */
    public function statistics(Request $request)
    {
        $stats = $this->incidentService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Export incident report
     */
    public function exportReport(Request $request, $id)
    {
        $format = $request->format ?? 'pdf';
        $report = $this->incidentService->generateReport($id, $format);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}