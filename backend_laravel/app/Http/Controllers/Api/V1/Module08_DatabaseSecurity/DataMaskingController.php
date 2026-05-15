<?php

namespace App\Http\Controllers\Api\V1\Module08_DatabaseSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DataMaskingService;

class DataMaskingController extends Controller
{
    protected $maskingService;

    public function __construct(DataMaskingService $maskingService)
    {
        $this->maskingService = $maskingService;
    }

    /**
     * Áp dụng masking rules
     */
    public function applyMasking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
            'column' => 'required|string',
            'mask_type' => 'required|in:full,partial,email,phone,credit_card,ssn',
            'mask_value' => 'nullable|string',
            'roles' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->maskingService->applyMasking([
            'table' => $request->table,
            'column' => $request->column,
            'mask_type' => $request->mask_type,
            'mask_value' => $request->mask_value,
            'roles' => $request->roles ?? [],
        ]);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Áp dụng masking thành công'
        ]);
    }

    /**
     * Danh sách masking rules
     */
    public function listRules(Request $request)
    {
        $rules = $this->maskingService->getAllRules();

        return response()->json([
            'success' => true,
            'data' => $rules
        ]);
    }

    /**
     * Xóa masking rule
     */
    public function deleteRule($id)
    {
        $result = $this->maskingService->deleteRule($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa rule thành công' : 'Xóa thất bại'
        ]);
    }

    /**
     * Test masking
     */
    public function testMasking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|string',
            'mask_type' => 'required|in:full,partial,email,phone,credit_card,ssn',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $masked = $this->maskingService->testMasking(
            $request->value,
            $request->mask_type
        );

        return response()->json([
            'success' => true,
            'data' => [
                'original' => $request->value,
                'masked' => $masked,
            ]
        ]);
    }

    /**
     * Dynamic data masking
     */
    public function dynamicMasking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string',
            'user_role' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->maskingService->dynamicMask(
            $request->query,
            $request->user_role
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}