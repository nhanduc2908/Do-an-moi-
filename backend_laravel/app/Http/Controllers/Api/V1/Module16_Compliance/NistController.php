<?php

namespace App\Http\Controllers\Api\V1\Module16_Compliance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NistService;

class NistController extends Controller
{
    protected $nistService;

    public function __construct(NistService $nistService)
    {
        $this->nistService = $nistService;
    }

    /**
     * CSF Framework
     */
    public function csfFramework()
    {
        $framework = $this->nistService->getCsfFramework();

        return response()->json([
            'success' => true,
            'data' => $framework
        ]);
    }

    /**
     * CSF Functions
     */
    public function csfFunctions()
    {
        $functions = $this->nistService->getCsfFunctions();

        return response()->json([
            'success' => true,
            'data' => $functions
        ]);
    }

    /**
     * CSF Categories
     */
    public function csfCategories(Request $request)
    {
        $categories = $this->nistService->getCsfCategories($request->function_id);

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * CSF Subcategories
     */
    public function csfSubcategories(Request $request)
    {
        $subcategories = $this->nistService->getCsfSubcategories($request->category_id);

        return response()->json([
            'success' => true,
            'data' => $subcategories
        ]);
    }

    /**
     * CSF Tier assessment
     */
    public function csfTierAssessment()
    {
        $assessment = $this->nistService->performTierAssessment();

        return response()->json([
            'success' => true,
            'data' => $assessment
        ]);
    }

    /**
     * CSF Profile
     */
    public function csfProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_tier' => 'required|array',
            'target_tier' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = $this->nistService->createCsfProfile(
            $request->current_tier,
            $request->target_tier
        );

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    /**
     * 800-53 controls
     */
    public function controls80053(Request $request)
    {
        $controls = $this->nistService->getControls80053([
            'family' => $request->family,
        ]);

        return response()->json([
            'success' => true,
            'data' => $controls
        ]);
    }

    /**
     * Risk assessment
     */
    public function riskAssessment(Request $request)
    {
        $assessment = $this->nistService->performRiskAssessment();

        return response()->json([
            'success' => true,
            'data' => $assessment
        ]);
    }

    /**
     * Generate report
     */
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:csf_profile,gap_analysis,risk_assessment',
            'format' => 'nullable|in:pdf,docx,xlsx',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->nistService->generateReport(
            $request->report_type,
            $request->format ?? 'pdf'
        );

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}
