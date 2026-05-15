<?php

namespace App\Http\Controllers\Api\V1\Module24_DevSecOps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\TerraformScanService;

class TerraformScanController extends Controller
{
    protected $tfService;

    public function __construct(TerraformScanService $tfService)
    {
        $this->tfService = $tfService;
    }

    /**
     * Quét template
     */
    public function scanTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template' => 'required|string',
            'variables' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->tfService->scan($request->template, $request->variables ?? []);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Analyze plan
     */
    public function analyzePlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_json' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $analysis = $this->tfService->analyzePlan($request->plan_json);

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Provider security
     */
    public function providerSecurity()
    {
        $providers = $this->tfService->getProviderSecurity();

        return response()->json([
            'success' => true,
            'data' => $providers
        ]);
    }

    /**
     * Module security
     */
    public function moduleSecurity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_source' => 'required|string',
            'version' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $security = $this->tfService->checkModuleSecurity(
            $request->module_source,
            $request->version
        );

        return response()->json([
            'success' => true,
            'data' => $security
        ]);
    }

    /**
     * Validate HCL
     */
    public function validateHcl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hcl_content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validation = $this->tfService->validateHcl($request->hcl_content);

        return response()->json([
            'success' => true,
            'data' => $validation
        ]);
    }
}