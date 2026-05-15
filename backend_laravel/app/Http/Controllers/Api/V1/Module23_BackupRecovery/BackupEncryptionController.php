<?php

namespace App\Http\Controllers\Api\V1\Module23_BackupRecovery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BackupEncryptionService;

class BackupEncryptionController extends Controller
{
    protected $encryptionService;

    public function __construct(BackupEncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * Enable encryption
     */
    public function enableEncryption(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'algorithm' => 'nullable|in:aes-256-gcm,aes-256-cbc,chacha20',
            'key_rotation_days' => 'nullable|integer|min=30|max=365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->encryptionService->enable($request->all());

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Backup encryption enabled'
        ]);
    }

    /**
     * Disable encryption
     */
    public function disableEncryption()
    {
        $result = $this->encryptionService->disable();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Backup encryption disabled' : 'Disable failed'
        ]);
    }

    /**
     * Rotate key
     */
    public function rotateKey()
    {
        $result = $this->encryptionService->rotateKey();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Encryption key rotated' : 'Rotation failed'
        ]);
    }

    /**
     * Encryption status
     */
    public function encryptionStatus()
    {
        $status = $this->encryptionService->getStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Verify integrity
     */
    public function verifyIntegrity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'backup_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->encryptionService->verifyIntegrity($request->backup_id);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}