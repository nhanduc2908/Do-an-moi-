<?php

namespace App\Http\Controllers\RoleBased;

use App\Http\Controllers\Controller;
use App\Services\RoleBased\AuditorService;
use Illuminate\Http\Request;

class AuditorController extends Controller
{
    protected $auditorService;

    public function __construct(AuditorService $auditorService)
    {
        $this->auditorService = $auditorService;
        $this->middleware(['auth', 'role:auditor']);
    }

    public function index()
    {
        $data = $this->auditorService->getDashboardData();
        return view('admin.roles.auditor.index', $data);
    }

    public function logReviewer(Request $request)
    {
        $data = $this->auditorService->getLogData($request);
        return view('admin.roles.auditor.log-reviewer', $data);
    }

    public function accessReports(Request $request)
    {
        $data = $this->auditorService->getAccessReports($request);
        return view('admin.roles.auditor.access-reports', $data);
    }

    public function complianceChecker()
    {
        $data = $this->auditorService->getComplianceCheckData();
        return view('admin.roles.auditor.compliance-checker', $data);
    }

    public function exportLogs(Request $request)
    {
        return $this->auditorService->exportLogs($request->all());
    }

    public function getAuditTrail(Request $request)
    {
        return response()->json($this->auditorService->getAuditTrail($request));
    }

    public function getUserAccessReport($userId)
    {
        return response()->json($this->auditorService->getUserAccessReport($userId));
    }

    public function runComplianceCheck(Request $request)
    {
        $result = $this->auditorService->runComplianceCheck($request->standard);
        return response()->json($result);
    }
}