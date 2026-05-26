<?php

namespace App\Http\Controllers\RoleBased;

use App\Http\Controllers\Controller;
use App\Services\RoleBased\RiskManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RiskManagerController extends Controller
{
    protected $riskManagerService;

    public function __construct(RiskManagerService $riskManagerService)
    {
        $this->riskManagerService = $riskManagerService;
        $this->middleware(['auth', 'role:risk_manager']);
    }

    public function index()
    {
        $data = $this->riskManagerService->getDashboardData();
        return view('admin.roles.risk-manager.index', $data);
    }

    public function riskRegister()
    {
        $data = $this->riskManagerService->getRiskRegister();
        return view('admin.roles.risk-manager.risk-register', $data);
    }

    public function riskAssessment()
    {
        $data = $this->riskManagerService->getRiskAssessmentData();
        return view('admin.roles.risk-manager.risk-assessment', $data);
    }

    public function riskTreatment()
    {
        $data = $this->riskManagerService->getRiskTreatmentData();
        return view('admin.roles.risk-manager.risk-treatment', $data);
    }

    public function createRisk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'likelihood' => 'required|integer|min:1|max:5',
            'impact' => 'required|integer|min:1|max:5',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->riskManagerService->createRisk($request->all());
        return response()->json($result);
    }

    public function updateRisk(Request $request, $id)
    {
        $result = $this->riskManagerService->updateRisk($id, $request->all());
        return response()->json($result);
    }

    public function deleteRisk($id)
    {
        $result = $this->riskManagerService->deleteRisk($id);
        return response()->json($result);
    }

    public function calculateRiskScore(Request $request)
    {
        $result = $this->riskManagerService->calculateRiskScore($request->likelihood, $request->impact);
        return response()->json($result);
    }

    public function getRiskMatrix()
    {
        return response()->json($this->riskManagerService->getRiskMatrix());
    }

    public function generateRiskReport()
    {
        $result = $this->riskManagerService->generateRiskReport();
        return response()->json($result);
    }
}