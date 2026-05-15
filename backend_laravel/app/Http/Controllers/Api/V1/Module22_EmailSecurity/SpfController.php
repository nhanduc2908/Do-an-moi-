<?php

namespace App\Http\Controllers\Api\V1\Module22_EmailSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SpfService;

class SpfController extends Controller
{
    protected $spfService;

    public function __construct(SpfService $spfService)
    {
        $this->spfService = $spfService;
    }

    /**
     * Check SPF
     */
    public function checkSpf(Request $request)
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

        $result = $this->spfService->check($request->domain);

        return response()->json([
            'success' => true,
            'data' => [
                'domain' => $request->domain,
                'has_spf' => $result['has_spf'],
                'spf_record' => $result['record'] ?? null,
                'valid' => $result['valid'],
                'issues' => $result['issues'] ?? [],
                'suggestions' => $result['suggestions'] ?? [],
            ]
        ]);
    }

    /**
     * Validate SPF record
     */
    public function validateRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'record' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validation = $this->spfService->validateRecord($request->record);

        return response()->json([
            'success' => true,
            'data' => $validation
        ]);
    }

    /**
     * SPF report
     */
    public function spfReport($domain)
    {
        $report = $this->spfService->generateReport($domain);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Generate SPF record
     */
    public function generateRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string',
            'ip_addresses' => 'nullable|array',
            'include_domains' => 'nullable|array',
            'mx_mechanism' => 'nullable|boolean',
            'a_mechanism' => 'nullable|boolean',
            'ptr_mechanism' => 'nullable|boolean',
            'redirect' => 'nullable|string',
            'hard_fail' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $record = $this->spfService->generateRecord($request->all());

        return response()->json([
            'success' => true,
            'data' => [
                'record' => $record,
                'domain' => $request->domain,
                'type' => 'TXT',
            ]
        ]);
    }
}