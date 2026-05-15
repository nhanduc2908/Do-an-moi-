<?php

namespace App\Http\Controllers\Api\V1\Module08_DatabaseSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DatabaseEncryptionService;

class DbEncryptionController extends Controller
{
    protected $dbEncryptionService;

    public function __construct(DatabaseEncryptionService $dbEncryptionService)
    {
        $this->dbEncryptionService = $dbEncryptionService;
    }

    /**
     * Mã hóa cột dữ liệu
     */
    public function encryptColumn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
            'column' => 'required|string',
            'algorithm' => 'nullable|in:aes-256-cbc,aes-128-cbc',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->dbEncryptionService->encryptColumn(
            $request->table,
            $request->column,
            $request->algorithm ?? 'aes-256-cbc'
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Mã hóa cột thành công'
        ]);
    }

    /**
     * Giải mã cột dữ liệu
     */
    public function decryptColumn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
            'column' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->dbEncryptionService->decryptColumn(
            $request->table,
            $request->column
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Kiểm tra trạng thái mã hóa
     */
    public function encryptionStatus(Request $request)
    {
        $status = $this->dbEncryptionService->getEncryptionStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Kích hoạt TDE (Transparent Data Encryption)
     */
    public function enableTde(Request $request)
    {
        $result = $this->dbEncryptionService->enableTde();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'TDE đã được kích hoạt' : 'Không thể kích hoạt TDE'
        ]);
    }

    /**
     * Backup encryption keys
     */
    public function backupKeys(Request $request)
    {
        $backup = $this->dbEncryptionService->backupEncryptionKeys();

        return response()->json([
            'success' => true,
            'data' => $backup,
            'message' => 'Backup keys thành công'
        ]);
    }
}