<?php

namespace App\Http\Controllers\Api\V1\Module02_Encryption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\VaultItem;
use App\Models\VaultFolder;
use App\Services\VaultService;

class VaultController extends Controller
{
    protected $vaultService;

    public function __construct(VaultService $vaultService)
    {
        $this->vaultService = $vaultService;
    }

    /**
     * Danh sách folders
     */
    public function folders(Request $request)
    {
        $folders = VaultFolder::where('user_id', auth()->id())
            ->withCount('items')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $folders
        ]);
    }

    /**
     * Tạo folder
     */
    public function createFolder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'parent_id' => 'nullable|exists:vault_folders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $folder = VaultFolder::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $folder,
            'message' => 'Tạo folder thành công'
        ]);
    }

    /**
     * Danh sách items trong vault
     */
    public function items(Request $request)
    {
        $query = VaultItem::where('user_id', auth()->id());

        if ($request->folder_id) {
            $query->where('folder_id', $request->folder_id);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('username', 'like', "%{$request->search}%");
            });
        }

        $items = $query->orderBy('name')->paginate($request->per_page ?? 20);

        // Decrypt sensitive data
        foreach ($items as $item) {
            $item->decrypted_value = $this->vaultService->decryptValue($item);
        }

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Tạo item mới
     */
    public function storeItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=200',
            'type' => 'required|in:password,note,credit_card,file,api_key',
            'value' => 'required|string',
            'folder_id' => 'nullable|exists:vault_folders,id',
            'username' => 'nullable|string|required_if:type,password',
            'url' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $item = $this->vaultService->storeItem([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'folder_id' => $request->folder_id,
            'username' => $request->username,
            'url' => $request->url,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'data' => $item,
            'message' => 'Lưu vault thành công'
        ], 201);
    }

    /**
     * Chi tiết item
     */
    public function showItem($id)
    {
        $item = VaultItem::where('user_id', auth()->id())
            ->findOrFail($id);

        $item->decrypted_value = $this->vaultService->decryptValue($item);

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    /**
     * Cập nhật item
     */
    public function updateItem(Request $request, $id)
    {
        $item = VaultItem::where('user_id', auth()->id())
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max=200',
            'value' => 'sometimes|string',
            'username' => 'nullable|string',
            'url' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $updated = $this->vaultService->updateItem($item, $request->all());

        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Cập nhật thành công'
        ]);
    }

    /**
     * Xóa item
     */
    public function deleteItem($id)
    {
        $item = VaultItem::where('user_id', auth()->id())
            ->findOrFail($id);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công'
        ]);
    }

    /**
     * Share item
     */
    public function shareItem(Request $request, $id)
    {
        $item = VaultItem::where('user_id', auth()->id())
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'permission' => 'required|in:view,edit',
            'expires_in_hours' => 'nullable|integer|min=1|max=168',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $share = $this->vaultService->shareItem($item, $request->user_id, $request->permission, $request->expires_in_hours);

        return response()->json([
            'success' => true,
            'data' => $share,
            'message' => 'Chia sẻ thành công'
        ]);
    }

    /**
     * Export vault
     */
    public function export(Request $request)
    {
        $format = $request->format ?? 'json';
        
        $export = $this->vaultService->exportVault(auth()->id(), $format);

        return response()->json([
            'success' => true,
            'data' => [
                'file' => $export['file'],
                'format' => $format,
                'encrypted' => $export['encrypted'],
                'download_url' => $export['download_url'],
            ]
        ]);
    }

    /**
     * Import vault
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:json,csv',
            'encryption_key' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->vaultService->importVault(
            $request->file('file'),
            auth()->id(),
            $request->encryption_key
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Import thành công'
        ]);
    }

    /**
     * Generate strong password
     */
    public function generatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'length' => 'nullable|integer|min:8|max:64',
            'include_uppercase' => 'nullable|boolean',
            'include_lowercase' => 'nullable|boolean',
            'include_numbers' => 'nullable|boolean',
            'include_symbols' => 'nullable|boolean',
        ]);

        $password = $this->vaultService->generatePassword([
            'length' => $request->length ?? 16,
            'include_uppercase' => $request->include_uppercase ?? true,
            'include_lowercase' => $request->include_lowercase ?? true,
            'include_numbers' => $request->include_numbers ?? true,
            'include_symbols' => $request->include_symbols ?? true,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'password' => $password,
                'strength' => $this->vaultService->checkPasswordStrength($password),
            ]
        ]);
    }

    /**
     * Check password strength
     */
    public function checkPasswordStrength(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $strength = $this->vaultService->checkPasswordStrength($request->password);

        return response()->json([
            'success' => true,
            'data' => $strength
        ]);
    }
}