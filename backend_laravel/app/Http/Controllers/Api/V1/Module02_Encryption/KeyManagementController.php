<?php

namespace App\Http\Controllers\Api\V1\Module02_Encryption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EncryptionKey;
use App\Services\KeyManagementService;

class KeyManagementController extends Controller
{
    protected $keyManagement;

    public function __construct(KeyManagementService $keyManagement)
    {
        $this->keyManagement = $keyManagement;
    }

    /**
     * Danh sách keys
     */
    public function index(Request $request)
    {
        $keys = EncryptionKey::when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $keys->map(function($key) {
                return [
                    'id' => $key->id,
                    'name' => $key->name,
                    'algorithm' => $key->algorithm,
                    'status' => $key->status,
                    'created_at' => $key->created_at,
                    'expires_at' => $key->expires_at,
                    'last_rotated_at' => $key->last_rotated_at,
                ];
            }),
            'meta' => [
                'current_page' => $keys->currentPage(),
                'last_page' => $keys->lastPage(),
                'total' => $keys->total(),
            ]
        ]);
    }

    /**
     * Tạo key mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'algorithm' => 'required|in:aes-128-cbc,aes-256-cbc,chacha20,rsa-2048,rsa-4096',
            'key_size' => 'nullable|integer',
            'expires_in_days' => 'nullable|integer|min=1|max=365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $key = $this->keyManagement->generateKey(
            $request->name,
            $request->algorithm,
            $request->key_size,
            $request->expires_in_days ?? 90
        );

        return response()->json([
            'success' => true,
            'data' => $key,
            'message' => 'Tạo key thành công'
        ], 201);
    }

    /**
     * Chi tiết key
     */
    public function show($id)
    {
        $key = EncryptionKey::findOrFail($id);

        // Decrypt key value before sending (only for admin)
        if (auth()->user()->hasPermission('view_key_value')) {
            $key->key_value = $this->keyManagement->decryptKeyValue($key);
        }

        return response()->json([
            'success' => true,
            'data' => $key
        ]);
    }

    /**
     * Xoay vòng key
     */
    public function rotate($id)
    {
        $key = EncryptionKey::findOrFail($id);

        $newKey = $this->keyManagement->rotateKey($key);

        return response()->json([
            'success' => true,
            'data' => $newKey,
            'message' => 'Xoay vòng key thành công'
        ]);
    }

    /**
     * Vô hiệu hóa key
     */
    public function deactivate($id)
    {
        $key = EncryptionKey::findOrFail($id);

        $this->keyManagement->deactivateKey($key);

        return response()->json([
            'success' => true,
            'message' => 'Key đã được vô hiệu hóa'
        ]);
    }

    /**
     * Xóa key
     */
    public function destroy($id)
    {
        $key = EncryptionKey::findOrFail($id);

        if ($key->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa key đang hoạt động'
            ], 400);
        }

        $key->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa key thành công'
        ]);
    }

    /**
     * Backup keys
     */
    public function backup(Request $request)
    {
        $keys = EncryptionKey::where('status', 'active')->get();
        
        $backup = $this->keyManagement->backupKeys($keys);

        return response()->json([
            'success' => true,
            'data' => [
                'backup_file' => $backup['file'],
                'encryption_key' => $backup['encryption_key'],
                'expires_at' => $backup['expires_at'],
            ],
            'message' => 'Backup keys thành công'
        ]);
    }
}