<?php

namespace App\Http\Controllers\Api\V1\Module02_Encryption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DigitalSignatureService;

class DigitalSignatureController extends Controller
{
    protected $signatureService;

    public function __construct(DigitalSignatureService $signatureService)
    {
        $this->signatureService = $signatureService;
    }

    /**
     * Tạo chữ ký số
     */
    public function sign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|string',
            'algorithm' => 'nullable|in:rsa-sha256,ecdsa-sha256,ed25519',
            'key_id' => 'nullable|exists:signature_keys,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $signature = $this->signatureService->sign(
            $request->data,
            auth()->id(),
            $request->algorithm ?? 'rsa-sha256',
            $request->key_id
        );

        return response()->json([
            'success' => true,
            'data' => [
                'signature' => $signature['signature'],
                'algorithm' => $signature['algorithm'],
                'key_id' => $signature['key_id'],
                'timestamp' => $signature['timestamp'],
            ]
        ]);
    }

    /**
     * Xác minh chữ ký số
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|string',
            'signature' => 'required|string',
            'public_key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $isValid = $this->signatureService->verify(
            $request->data,
            $request->signature,
            $request->public_key
        );

        return response()->json([
            'success' => true,
            'data' => [
                'is_valid' => $isValid,
                'verified_at' => now(),
            ]
        ]);
    }

    /**
     * Tạo cặp khóa cho chữ ký số
     */
    public function generateKeyPair(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'algorithm' => 'required|in:rsa-2048,rsa-4096,ecdsa-p256,ecdsa-p384,ed25519',
            'name' => 'required|string|max=100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $keyPair = $this->signatureService->generateKeyPair(
            auth()->id(),
            $request->algorithm,
            $request->name
        );

        return response()->json([
            'success' => true,
            'data' => [
                'public_key' => $keyPair['public_key'],
                'key_id' => $keyPair['key_id'],
                'fingerprint' => $keyPair['fingerprint'],
            ],
            'message' => 'Tạo cặp khóa thành công'
        ]);
    }

    /**
     * Ký file
     */
    public function signFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
            'key_id' => 'required|exists:signature_keys,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');
        $fileContent = file_get_contents($file->getPathname());

        $signature = $this->signatureService->signFile(
            $fileContent,
            $request->key_id
        );

        return response()->json([
            'success' => true,
            'data' => [
                'signature' => $signature['signature'],
                'file_name' => $file->getClientOriginalName(),
                'file_hash' => $signature['file_hash'],
            ]
        ]);
    }

    /**
     * Xác minh chữ ký file
     */
    public function verifyFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
            'signature' => 'required|string',
            'public_key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');
        $fileContent = file_get_contents($file->getPathname());

        $isValid = $this->signatureService->verifyFile(
            $fileContent,
            $request->signature,
            $request->public_key
        );

        return response()->json([
            'success' => true,
            'data' => [
                'is_valid' => $isValid,
                'file_name' => $file->getClientOriginalName(),
                'verified_at' => now(),
            ]
        ]);
    }

    /**
     * Danh sách khóa chữ ký
     */
    public function listKeys(Request $request)
    {
        $keys = $this->signatureService->getUserKeys(auth()->id());

        return response()->json([
            'success' => true,
            'data' => $keys
        ]);
    }
}