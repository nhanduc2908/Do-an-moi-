<?php

namespace App\Http\Controllers\Api\V1\Module22_EmailSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DkimService;

class DkimController extends Controller
{
    protected $dkimService;

    public function __construct(DkimService $dkimService)
    {
        $this->dkimService = $dkimService;
    }

    /**
     * Verify DKIM
     */
    public function verifyDkim(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string',
            'selector' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->dkimService->verify($request->domain, $request->selector);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Generate DKIM keys
     */
    public function generateKeys(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string',
            'selector' => 'required|string',
            'key_size' => 'nullable|in:1024,2048,4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $keys = $this->dkimService->generateKeys(
            $request->domain,
            $request->selector,
            $request->key_size ?? 2048
        );

        return response()->json([
            'success' => true,
            'data' => [
                'private_key' => $keys['private'],
                'public_key' => $keys['public'],
                'dns_record' => $keys['dns_record'],
                'selector' => $request->selector,
            ],
            'message' => 'Lưu private_key một cách an toàn'
        ]);
    }

    /**
     * DKIM status
     */
    public function dkimStatus($domain)
    {
        $status = $this->dkimService->getStatus($domain);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Rotate keys
     */
    public function rotateKeys(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string',
            'selector' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->dkimService->rotateKeys($request->domain, $request->selector);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Xoay vòng DKIM keys thành công'
        ]);
    }
}