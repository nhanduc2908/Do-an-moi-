<?php

namespace App\Http\Controllers\RoleBased;

use App\Http\Controllers\Controller;
use App\Services\RoleBased\ViewerService;
use Illuminate\Http\Request;

class ViewerController extends Controller
{
    protected $viewerService;

    public function __construct(ViewerService $viewerService)
    {
        $this->viewerService = $viewerService;
        $this->middleware(['auth', 'role:viewer']);
    }

    public function index()
    {
        $data = $this->viewerService->getDashboardData();
        return view('admin.roles.viewer.index', $data);
    }

    public function securityDashboard()
    {
        $data = $this->viewerService->getSecurityDashboardData();
        return view('admin.roles.viewer.security-dashboard', $data);
    }

    public function reportViewer(Request $request)
    {
        $data = $this->viewerService->getReportData($request);
        return view('admin.roles.viewer.report-viewer', $data);
    }

    public function viewReport($reportId)
    {
        return $this->viewerService->viewReport($reportId);
    }

    public function getSecurityScore()
    {
        return response()->json($this->viewerService->getCurrentSecurityScore());
    }

    public function getDashboardWidgets()
    {
        return response()->json($this->viewerService->getDashboardWidgets());
    }
}