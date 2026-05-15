<?php

namespace App\Http\Controllers\Api\V1\Module24_DevSecOps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\IacSecurityService;

class IacSecurityController extends Controller
{
    protected $iacService;

    public function __construct(IacSecurityService $iacService)
    {
        $this->iacService = $iacService;
    }

    /**
     * Quét template
     */
    public function scanTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template' => 'required|string',
            'type' => 'required|in:cloudformation,terraform,arm,helm',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->iacService->scanTemplate($request->template, $request->type);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Kết quả quét
     */
    public function scanResult($scanId)
    {
        $result = $this->iacService->getScanResult($scanId);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Validate template
     */
    public function validateTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template' => 'required|string',
            'type' => 'required|in:cloudformation,terraform,arm,helm',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->iacService->validate($request->template, $request->type);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Policies
     */
    public function policies()
    {
        $policies = $this->iacService->getPolicies();

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }

    /**
     * Create policy
     */
    public function createPolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'rule' => 'required|string',
            'severity' => 'required|in:critical,high,medium,low',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $policy = $this->iacService->createPolicy($request->all());

        return response()->json([
            'success' => true,
            'data' => $policy,
            'message' => 'Policy created'
        ]);
    }

    /**
     * Common misconfigurations
     */
    public function commonMisconfigurations()
    {
        $misconfigs = $this->iacService->getCommonMisconfigurations();

        return response()->json([
            'success' => true,
            'data' => $misconfigs
        ]);
    }
}