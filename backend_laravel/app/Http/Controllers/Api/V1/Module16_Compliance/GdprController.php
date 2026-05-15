<?php

namespace App\Http\Controllers\Api\V1\Module16_Compliance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\GdprService;

class GdprController extends Controller
{
    protected $gdprService;

    public function __construct(GdprService $gdprService)
    {
        $this->gdprService = $gdprService;
    }

    /**
     * Data inventory
     */
    public function dataInventory(Request $request)
    {
        $inventory = $this->gdprService->getDataInventory([
            'data_type' => $request->data_type,
            'category' => $request->category,
        ]);

        return response()->json([
            'success' => true,
            'data' => $inventory
        ]);
    }

    /**
     * Add data record
     */
    public function addDataRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_category' => 'required|string',
            'data_type' => 'required|string',
            'purpose' => 'required|string',
            'legal_basis' => 'required|string',
            'data_subjects' => 'required|array',
            'retention_period' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $record = $this->gdprService->addDataRecord($request->all());

        return response()->json([
            'success' => true,
            'data' => $record,
            'message' => 'Thêm data record thành công'
        ]);
    }

    /**
     * Data Subject Access Request (DSAR)
     */
    public function dsar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|string',
            'request_type' => 'required|in:access,rectification,erasure,restrict,portability',
            'request_details' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $request = $this->gdprService->submitDsar($request->all());

        return response()->json([
            'success' => true,
            'data' => $request,
            'message' => 'DSAR đã được gửi'
        ]);
    }

    /**
     * Process DSAR
     */
    public function processDsar($id)
    {
        $result = $this->gdprService->processDsar($id);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'DSAR đã được xử lý'
        ]);
    }

    /**
     * Data Protection Impact Assessment (DPIA)
     */
    public function dpia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'processing_activity' => 'required|string',
            'risk_level' => 'required|in:low,medium,high',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $dpia = $this->gdprService->createDpia($request->all());

        return response()->json([
            'success' => true,
            'data' => $dpia,
            'message' => 'DPIA đã được tạo'
        ]);
    }

    /**
     * Data breach notification
     */
    public function breachNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'data_subjects_count' => 'required|integer',
            'data_categories' => 'required|array',
            'likely_risk' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $notification = $this->gdprService->reportBreach($request->all());

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Đã ghi nhận data breach'
        ]);
    }

    /**
     * Consent management
     */
    public function consentManagement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'purposes' => 'required|array',
            'purposes.*.id' => 'required|string',
            'purposes.*.consent' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $consent = $this->gdprService->manageConsent($request->all());

        return response()->json([
            'success' => true,
            'data' => $consent,
            'message' => 'Cập nhật consent thành công'
        ]);
    }

    /**
     * Data mapping
     */
    public function dataMapping()
    {
        $mapping = $this->gdprService->getDataFlowMapping();

        return response()->json([
            'success' => true,
            'data' => $mapping
        ]);
    }

    /**
     * Compliance checklist
     */
    public function complianceChecklist()
    {
        $checklist = $this->gdprService->getComplianceChecklist();

        return response()->json([
            'success' => true,
            'data' => $checklist
        ]);
    }

    /**
     * Generate report
     */
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:compliance,breach,dsar,dpia',
            'format' => 'nullable|in:pdf,docx,csv',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->gdprService->generateReport(
            $request->report_type,
            $request->format ?? 'pdf'
        );

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}