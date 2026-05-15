<?php

namespace App\Http\Controllers\Api\V1\Module16_Compliance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ComplianceChecklistService;

class ComplianceChecklistController extends Controller
{
    protected $checklistService;

    public function __construct(ComplianceChecklistService $checklistService)
    {
        $this->checklistService = $checklistService;
    }

    /**
     * Danh sách checklists
     */
    public function checklists(Request $request)
    {
        $checklists = $this->checklistService->getChecklists([
            'framework' => $request->framework,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $checklists
        ]);
    }

    /**
     * Chi tiết checklist
     */
    public function checklistDetail($id)
    {
        $checklist = $this->checklistService->getChecklistDetail($id);

        return response()->json([
            'success' => true,
            'data' => $checklist
        ]);
    }

    /**
     * Tạo checklist
     */
    public function createChecklist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'framework' => 'required|string',
            'items' => 'required|array',
            'items.*.requirement' => 'required|string',
            'items.*.control' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $checklist = $this->checklistService->createChecklist($request->all());

        return response()->json([
            'success' => true,
            'data' => $checklist,
            'message' => 'Tạo checklist thành công'
        ]);
    }

    /**
     * Update checklist item
     */
    public function updateChecklistItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:compliant,non_compliant,partial,not_applicable',
            'evidence' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $item = $this->checklistService->updateItem($id, $request->all());

        return response()->json([
            'success' => true,
            'data' => $item,
            'message' => 'Cập nhật checklist item thành công'
        ]);
    }

    /**
     * Overall compliance status
     */
    public function overallStatus(Request $request)
    {
        $status = $this->checklistService->getOverallStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Evidence upload
     */
    public function uploadEvidence(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:102400',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evidence = $this->checklistService->uploadEvidence(
            $id,
            $request->file('file'),
            $request->description
        );

        return response()->json([
            'success' => true,
            'data' => $evidence,
            'message' => 'Upload evidence thành công'
        ]);
    }

    /**
     * Export checklist
     */
    public function exportChecklist(Request $request, $id)
    {
        $format = $request->format ?? 'pdf';
        $export = $this->checklistService->exportChecklist($id, $format);

        return response()->json([
            'success' => true,
            'data' => $export
        ]);
    }
}