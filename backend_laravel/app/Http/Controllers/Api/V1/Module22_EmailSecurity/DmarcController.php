<?php

namespace App\Http\Controllers\Api\V1\Module22_EmailSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DmarcService;

class DmarcController extends Controller
{
    protected $dmarcService;

    public function __construct(DmarcService $dmarcService)
    {
        $this->dmarcService = $dmarcService;
    }

    /**
     * Check DMARC
     */
    public function checkDmarc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->dmarcService->check($request->domain);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * DMARC reports
     */
    public function dmarcReports(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $reports = $this->dmarcService->getReports($request->all());

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    /**
     * Update DMARC policy
     */
    public function updatePolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string',
            'policy' => 'required|in:none,quarantine,reject',
            'subdomain_policy' => 'nullable|in:none,quarantine,reject',
            'percentage' => 'nullable|integer|min=0|max=100',
            'rua_emails' => 'nullable|array',
            'ruf_emails' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->dmarcService->updatePolicy($request->all());

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Cập nhật DMARC policy thành công'
        ]);
    }

    /**
     * Aggregate reports
     */
    public function aggregateReports($domain)
    {
        $reports = $this->dmarcService->getAggregateReports($domain);

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    /**
     * Configure RUA
     */
    public function configureRua(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string',
            'emails' => 'required|array',
            'emails.*' => 'email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->dmarcService->configureRua($request->domain, $request->emails);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Cấu hình RUA thành công'
        ]);
    }

    /**
     * DMARC summary
     */
    public function dmarcSummary($domain)
    {
        $summary = $this->dmarcService->getSummary($domain);

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
}