<?php

namespace App\Http\Controllers\RoleBased;

use App\Http\Controllers\Controller;
use App\Services\RoleBased\SecurityAnalystService;
use Illuminate\Http\Request;

class SecurityAnalystController extends Controller
{
    protected $securityAnalystService;

    public function __construct(SecurityAnalystService $securityAnalystService)
    {
        $this->securityAnalystService = $securityAnalystService;
        $this->middleware(['auth', 'role:security_analyst']);
    }

    public function index()
    {
        $data = $this->securityAnalystService->getDashboardData();
        return view('admin.roles.security-analyst.index', $data);
    }

    public function threatHunting()
    {
        $data = $this->securityAnalystService->getThreatHuntingData();
        return view('admin.roles.security-analyst.threat-hunting', $data);
    }

    public function siemDashboard()
    {
        $data = $this->securityAnalystService->getSiemData();
        return view('admin.roles.security-analyst.siem-dashboard', $data);
    }

    public function iocManagement()
    {
        $data = $this->securityAnalystService->getIocData();
        return view('admin.roles.security-analyst.ioc-management', $data);
    }

    public function searchIocs(Request $request)
    {
        $results = $this->securityAnalystService->searchIndicators($request->search);
        return response()->json($results);
    }

    public function addIoc(Request $request)
    {
        $result = $this->securityAnalystService->addIndicator($request->all());
        return response()->json($result);
    }

    public function getThreatIntel()
    {
        return response()->json($this->securityAnalystService->getThreatIntelligence());
    }

    public function runQuery(Request $request)
    {
        $results = $this->securityAnalystService->runSiemQuery($request->query_string);
        return response()->json($results);
    }
}