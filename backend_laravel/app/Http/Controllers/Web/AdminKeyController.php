<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EncryptionKey;
use App\Services\KeyManagementService;

class AdminKeyController extends Controller
{
    protected $keyService;

    public function __construct(KeyManagementService $keyService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->keyService = $keyService;
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
            ->paginate(15);

        return view('admin.keys.index', compact('keys'));
    }

    /**
     * Form tạo key
     */
    public function create()
    {
        return view('admin.keys.create');
    }

    /**
     * Tạo key mới
     */
    public function generate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'algorithm' => 'required|in:aes-128-cbc,aes-256-cbc,chacha20,rsa-2048,rsa-4096',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ]);

        $key = $this->keyService->generateKey(
            $request->name,
            $request->algorithm,
            null,
            $request->expires_in_days ?? 90
        );

        return redirect()->route('admin.keys.index')
            ->with('success', 'Key generated successfully.');
    }

    /**
     * Chi tiết key
     */
    public function show($id)
    {
        $key = EncryptionKey::findOrFail($id);

        return view('admin.keys.show', compact('key'));
    }

    /**
     * Xoay vòng key
     */
    public function rotate($id)
    {
        $key = EncryptionKey::findOrFail($id);
        
        $this->keyService->rotateKey($key);

        return redirect()->route('admin.keys.index')
            ->with('success', 'Key rotated successfully.');
    }

    /**
     * Vô hiệu hóa key
     */
    public function deactivate($id)
    {
        $key = EncryptionKey::findOrFail($id);
        
        $this->keyService->deactivateKey($key);

        return redirect()->route('admin.keys.index')
            ->with('success', 'Key deactivated successfully.');
    }

    /**
     * Xóa key
     */
    public function destroy($id)
    {
        $key = EncryptionKey::findOrFail($id);

        if ($key->is_active) {
            return back()->with('error', 'Cannot delete active key. Deactivate it first.');
        }

        $key->delete();

        return redirect()->route('admin.keys.index')
            ->with('success', 'Key deleted successfully.');
    }

    /**
     * Backup keys
     */
    public function backup()
    {
        $backup = $this->keyService->backupKeys();

        return response()->download($backup['path'], 'keys_backup_' . date('Ymd') . '.enc');
    }
}