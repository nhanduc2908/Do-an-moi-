<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReportService;

class AdminReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->reportService = $reportService;
    }

    /**
     * Dashboard reports
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Security report
     */
    public function security(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'format' => 'nullable|in:html,pdf,excel',
        ]);

        $data = $this->reportService->generateSecurityReport([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        if ($request->format === 'pdf') {
            return $this->exportPdf('security', $data);
        }

        if ($request->format === 'excel') {
            return $this->exportExcel('security', $data);
        }

        return view('admin.reports.security', $data);
    }

    /**
     * Incident report
     */
    public function incidents(Request $request)
    {
        $incidents = \App\Models\Incident::with('reporter')
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('occurred_at', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                $query->whereDate('occurred_at', '<=', $date);
            })
            ->when($request->severity, function($query, $severity) {
                $query->where('severity', $severity);
            })
            ->paginate(20);

        return view('admin.reports.incidents', compact('incidents'));
    }

    /**
     * Risk report
     */
    public function risks(Request $request)
    {
        $risks = \App\Models\Risk::with('asset')
            ->when($request->level, function($query, $level) {
                $query->where('level', $level);
            })
            ->orderBy('score', 'desc')
            ->paginate(20);

        return view('admin.reports.risks', compact('risks'));
    }

    /**
     * Compliance report
     */
    public function compliance(Request $request)
    {
        $request->validate([
            'framework' => 'required|in:iso27001,gdpr,pcidss,hipaa,nist',
        ]);

        $report = $this->reportService->generateComplianceReport($request->framework);

        return view('admin.reports.compliance', compact('report'));
    }

    /**
     * Audit report
     */
    public function audit(Request $request)
    {
        $logs = \App\Models\AuditLog::with('user')
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->when($request->event, function($query, $event) {
                $query->where('event', $event);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.reports.audit', compact('logs'));
    }

    /**
     * Executive summary
     */
    public function executive()
    {
        $summary = $this->reportService->generateExecutiveSummary();

        return view('admin.reports.executive', compact('summary'));
    }

    /**
     * Export report
     */
    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'format' => 'required|in:pdf,excel,csv',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $export = $this->reportService->exportReport($request->all());

        return response()->download($export['path'], $export['filename']);
    }

    /**
     * Schedule report
     */
    public function schedule(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'schedule' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
        ]);

        $schedule = $this->reportService->scheduleReport($request->all());

        return back()->with('success', 'Report scheduled successfully.');
    }

    /**
     * Danh sách scheduled reports
     */
    public function scheduled()
    {
        $schedules = $this->reportService->getScheduledReports();

        return view('admin.reports.scheduled', compact('schedules'));
    }

    /**
     * Delete scheduled report
     */
    public function deleteSchedule($id)
    {
        $result = $this->reportService->deleteSchedule($id);

        return back()->with('success', 'Schedule deleted successfully.');
    }

    private function exportPdf($type, $data)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("admin.reports.pdf.{$type}", $data);
        return $pdf->download("{$type}_report.pdf");
    }

    private function exportExcel($type, $data)
    {
        // return Excel::download(new ReportExport($data), "{$type}_report.xlsx");
        return back()->with('success', 'Export started.');
    }
}