<?php

namespace App\Http\Controllers\RoleBased;

use App\Http\Controllers\Controller;
use App\Services\RoleBased\IncidentResponderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncidentResponderController extends Controller
{
    protected $incidentResponderService;

    public function __construct(IncidentResponderService $incidentResponderService)
    {
        $this->incidentResponderService = $incidentResponderService;
        $this->middleware(['auth', 'role:incident_responder']);
    }

    public function index()
    {
        $data = $this->incidentResponderService->getDashboardData();
        return view('admin.roles.incident-responder.index', $data);
    }

    public function runbookExecutor()
    {
        $data = $this->incidentResponderService->getRunbooks();
        return view('admin.roles.incident-responder.runbook-executor', $data);
    }

    public function forensicTools()
    {
        $data = $this->incidentResponderService->getForensicData();
        return view('admin.roles.incident-responder.forensic-tools', $data);
    }

    public function containmentActions()
    {
        $data = $this->incidentResponderService->getContainmentActions();
        return view('admin.roles.incident-responder.containment-actions', $data);
    }

    public function executeRunbook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'runbook_id' => 'required|string',
            'incident_id' => 'required|uuid|exists:incidents,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->incidentResponderService->executeRunbook($request->runbook_id, $request->incident_id);
        return response()->json($result);
    }

    public function isolateSystem(Request $request)
    {
        $result = $this->incidentResponderService->isolateSystem($request->system_id);
        return response()->json($result);
    }

    public function blockIp(Request $request)
    {
        $result = $this->incidentResponderService->blockIpAddress($request->ip_address);
        return response()->json($result);
    }

    public function collectForensics(Request $request)
    {
        $result = $this->incidentResponderService->collectForensicData($request->incident_id);
        return response()->json($result);
    }
}