<?php

namespace App\Http\Controllers\Api\V1\Module03_WebSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\XssProtectionService;

class XssController extends Controller
{
    protected $xssService;

    public function __construct(XssProtectionService $xssService)
    {
        $this->xssService = $xssService;
    }

    /**
     * Kiểm tra XSS
     */
    public function test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'input' => 'required|string',
            'context' => 'nullable|in:html,attribute,script,style,url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->xssService->testXss(
            $request->input,
            $request->context ?? 'html'
        );

        return response()->json([
            'success' => true,
            'data' => [
                'is_vulnerable' => $result['vulnerable'],
                'risk_level' => $result['risk_level'],
                'sanitized' => $result['sanitized'],
                'payloads_found' => $result['payloads'],
            ]
        ]);
    }

    /**
     * Clean input
     */
    public function sanitize(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'input' => 'required|string',
            'rules' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $sanitized = $this->xssService->sanitize($request->input, $request->rules ?? []);

        return response()->json([
            'success' => true,
            'data' => [
                'original' => $request->input,
                'sanitized' => $sanitized,
            ]
        ]);
    }

    /**
     * CSP Header
     */
    public function csp(Request $request)
    {
        $policy = $this->xssService->getCspPolicy();

        return response()->json([
            'success' => true,
            'data' => $policy
        ]);
    }
}