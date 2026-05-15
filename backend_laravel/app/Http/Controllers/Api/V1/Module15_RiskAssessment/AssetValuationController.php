<?php

namespace App\Http\Controllers\Api\V1\Module15_RiskAssessment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AssetValuationService;

class AssetValuationController extends Controller
{
    protected $valuationService;

    public function __construct(AssetValuationService $valuationService)
    {
        $this->valuationService = $valuationService;
    }

    /**
     * Định giá asset
     */
    public function valueAsset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|string',
            'valuation_method' => 'required|in:monetary,impact_based,replacement_cost',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $valuation = $this->valuationService->valueAsset(
            $request->asset_id,
            $request->valuation_method
        );

        return response()->json([
            'success' => true,
            'data' => $valuation
        ]);
    }

    /**
     * Danh sách assets
     */
    public function listAssets(Request $request)
    {
        $assets = $this->valuationService->getAssets([
            'type' => $request->type,
            'criticality' => $request->criticality,
        ]);

        return response()->json([
            'success' => true,
            'data' => $assets
        ]);
    }

    /**
     * Tạo asset mới
     */
    public function createAsset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'type' => 'required|string',
            'value' => 'required|numeric|min=0',
            'criticality' => 'required|in:low,medium,high,critical',
            'owner' => 'required|string',
            'location' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $asset = $this->valuationService->createAsset($request->all());

        return response()->json([
            'success' => true,
            'data' => $asset,
            'message' => 'Tạo asset thành công'
        ]);
    }

    /**
     * Cập nhật asset
     */
    public function updateAsset(Request $request, $id)
    {
        $asset = $this->valuationService->updateAsset($id, $request->all());

        return response()->json([
            'success' => true,
            'data' => $asset,
            'message' => 'Cập nhật asset thành công'
        ]);
    }

    /**
     * Xóa asset
     */
    public function deleteAsset($id)
    {
        $result = $this->valuationService->deleteAsset($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa asset thành công' : 'Xóa thất bại'
        ]);
    }

    /**
     * Asset classification
     */
    public function classifyAssets()
    {
        $classification = $this->valuationService->classifyAssets();

        return response()->json([
            'success' => true,
            'data' => $classification
        ]);
    }

    /**
     * Business impact analysis
     */
    public function businessImpact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $impact = $this->valuationService->analyzeBusinessImpact($request->asset_id);

        return response()->json([
            'success' => true,
            'data' => $impact
        ]);
    }
}