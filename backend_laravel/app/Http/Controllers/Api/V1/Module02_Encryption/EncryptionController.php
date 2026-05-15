<?php

namespace App\Http\Controllers\Api\V1\Module02_Encryption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Services\EncryptionService;
use App\Exceptions\EncryptionException;

class EncryptionController extends Controller
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * Mã hóa dữ liệu
     */
    public function encrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|string',
            'algorithm' => 'nullable|in:aes-256-cbc,aes-128-cbc,chacha20',
            'key_id' => 'nullable|exists:encryption_keys,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->encryptionService->encrypt(
                $request->data,
                $request->algorithm ?? 'aes-256-cbc',
                $request->key_id
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'encrypted_data' => $result['encrypted'],
                    'iv' => $result['iv'] ?? null,
                    'key_id' => $result['key_id'],
                    'algorithm' => $result['algorithm'],
                ]
            ]);
        } catch (EncryptionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mã hóa thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Giải mã dữ liệu
     */
    public function decrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'encrypted_data' => 'required|string',
            'iv' => 'nullable|string',
            'key_id' => 'required|exists:encryption_keys,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $decrypted = $this->encryptionService->decrypt(
                $request->encrypted_data,
                $request->key_id,
                $request->iv
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'decrypted_data' => $decrypted
                ]
            ]);
        } catch (EncryptionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Giải mã thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mã hóa file
     */
    public function encryptFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:102400', // Max 100MB
            'algorithm' => 'nullable|in:aes-256-cbc,aes-128-cbc',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');
        $fileContent = file_get_contents($file->getPathname());

        try {
            $encrypted = $this->encryptionService->encryptFile(
                $fileContent,
                $request->algorithm ?? 'aes-256-cbc'
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'encrypted_file' => base64_encode($encrypted['data']),
                    'iv' => $encrypted['iv'],
                    'key_id' => $encrypted['key_id'],
                    'original_name' => $file->getClientOriginalName(),
                    'original_size' => $file->getSize(),
                ]
            ]);
        } catch (EncryptionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mã hóa file thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}