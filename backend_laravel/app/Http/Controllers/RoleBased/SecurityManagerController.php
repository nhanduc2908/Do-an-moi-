<?php

namespace App\Http\Controllers\RoleBased;

use App\Http\Controllers\Controller;
use App\Services\RoleBased\SecurityManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SecurityManagerController extends Controller
{
    protected $securityManagerService;

    public function __construct(SecurityManagerService $securityManagerService)
    {
        $this->securityManagerService = $securityManagerService;
        $this->middleware(['auth', 'role:security_manager']);
    }

    public function index()
    {
        $data = $this->securityManagerService->getDashboardData();
        return view('admin.roles.security-manager.index', $data);
    }

    public function incidentResponse()
    {
        $data = $this->securityManagerService->getIncidentData();
        return view('admin.roles.security-manager.incident-response', $data);
    }

    public function teamManagement()
    {
        $data = $this->securityManagerService->getTeamData();
        return view('admin.roles.security-manager.team-management', $data);
    }

    public function securityPolicies()
    {
        $data = $this->securityManagerService->getPolicies();
        return view('admin.roles.security-manager.security-policies', $data);
    }

    public function approvePolicy(Request $request)
    {
        $result = $this->securityManagerService->approvePolicy($request->policy_id);
        return response()->json($result);
    }

    public function assignIncident(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'incident_id' => 'required|uuid|exists:incidents,id',
            'user_id' => 'required|uuid|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->securityManagerService->assignIncident($request->incident_id, $request->user_id);
        return response()->json($result);
    }

    public function escalateIncident(Request $request)
    {
        $result = $this->securityManagerService->escalateIncident($request->incident_id, $request->reason);
        return response()->json($result);
    }

    public function getIncidentStats()
    {
        return response()->json($this->securityManagerService->getIncidentStatistics());
    }

    public function getTeamPerformance()
    {
        return response()->json($this->securityManagerService->getTeamPerformanceData());
    }

    public function createPolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->securityManagerService->createPolicy($request->all());
        return response()->json($result);
    }
}