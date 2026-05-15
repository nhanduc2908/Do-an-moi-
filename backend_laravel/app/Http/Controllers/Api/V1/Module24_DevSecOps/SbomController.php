<?php

namespace App\Http\Controllers\Api\V1\Module24_DevSecOps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SbomService;

class SbomController extends Controller
{
    protected $sbomService;

    public function __construct(SbomService $sbomService)
    {
        $this->sbomService = $sbomService;
    }

    /**
     * Generate SBOM
     */
    public function generateSbom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository_url' => 'required|url',
            'branch' => 'nullable|string',
            'format' => 'nullable|in:spdx,cyclonedx,json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $sbom = $this->sbomService->generate([
            'repository_url' => $request->repository_url,
            'branch' => $request->branch ?? 'main',
            'format' => $request->format ?? 'spdx',
        ]);

        return response()->json([
            'success' => true,
            'data' => $sbom
        ]);
    }

    /**
     * View SBOM
     */
    public function viewSbom($sbomId)
    {
        $sbom = $this->sbomService->getSbom($sbomId);

        return response()->json([
            'success' => true,
            'data' => $sbom
        ]);
    }

    /**
     * Validate SBOM
     */
    public function validateSbom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sbom_content' => 'required|string',
            'format' => 'required|in:spdx,cyclonedx,json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validation = $this->sbomService->validate($request->sbom_content, $request->format);

        return response()->json([
            'success' => true,
            'data' => $validation
        ]);
    }

    /**
     * Compare SBOMs
     */
    public function compareSboms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sbom1_id' => 'required|string',
            'sbom2_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $comparison = $this->sbomService->compare($request->sbom1_id, $request->sbom2_id);

        return response()->json([
            'success' => true,
            'data' => $comparison
        ]);
    }

    /**
     * Export SBOM
     */
    public function exportSbom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sbom_id' => 'required|string',
            'format' => 'required|in:spdx,cyclonedx,json,csv',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $export = $this->sbomService->export($request->sbom_id, $request->format);

        return response()->json([
            'success' => true,
            'data' => $export
        ]);
    }

    /**
     * SBOM vulnerabilities
     */
    public function sbomVulnerabilities($sbomId)
    {
        $vulnerabilities = $this->sbomService->getVulnerabilities($sbomId);

        return response()->json([
            'success' => true,
            'data' => $vulnerabilities
        ]);
    }
}