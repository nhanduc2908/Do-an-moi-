<?php

namespace App\Http\Controllers\RoleBased;

use App\Http\Controllers\Controller;
use App\Services\RoleBased\ComplianceOfficerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplianceOfficerController extends Controller
{
    protected $complianceOfficerService;

    public function __construct(ComplianceOfficerService $complianceOfficerService)
    {
        $this->complianceOfficerService = $complianceOfficerService;
        $this->middleware(['auth', 'role:compliance_officer']);
    }

    public function index()
    {
        $data = $this->complianceOfficerService->getDashboardData();
        return view('admin.roles.compliance-officer.index', $data);
    }

    public function auditSchedule()
    {
        $data = $this->complianceOfficerService->getAuditSchedule();
        return view('admin.roles.compliance-officer.audit-schedule', $data);
    }

    public function evidenceCollection()
    {
        $data = $this->complianceOfficerService->getEvidenceData();
        return view('admin.roles.compliance-officer.evidence-collection', $data);
    }

    public function complianceReports()
    {
        $data = $this->complianceOfficerService->getComplianceReports();
        return view('admin.roles.compliance-officer.compliance-reports', $data);
    }

    public function uploadEvidence(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'control_id' => 'required|string',
            'evidence' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->complianceOfficerService->uploadEvidence($request->all(), $request->file('evidence'));
        return response()->json($result);
    }

    public function runAudit(Request $request)
    {
        $result = $this->complianceOfficerService->runAudit($request->standard);
        return response()->json($result);
    }

    public function getComplianceStatus($standard)
    {
        return response()->json($this->complianceOfficerService->getComplianceStatus($standard));
    }

    public function generateReport(Request $request)
    {
        $result = $this->complianceOfficerService->generateComplianceReport($request->all());
        return response()->json($result);
    }
}