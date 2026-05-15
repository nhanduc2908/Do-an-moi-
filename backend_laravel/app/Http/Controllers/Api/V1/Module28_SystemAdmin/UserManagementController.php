<?php

namespace App\Http\Controllers\Api\V1\Module28_SystemAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Services\UserManagementService;

class UserManagementController extends Controller
{
    protected $userService;

    public function __construct(UserManagementService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Danh sách users
     */
    public function index(Request $request)
    {
        $users = User::with('role')
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->role_id, function($query, $roleId) {
                $query->where('role_id', $roleId);
            })
            ->when($request->status, function($query, $status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Chi tiết user
     */
    public function show($id)
    {
        $user = User::with('role', 'sessions', 'devices')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Tạo user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Tạo user thành công'
        ]);
    }

    /**
     * Cập nhật user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max=255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'role_id' => 'nullable|exists:roles,id',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only(['name', 'email', 'role_id', 'is_active']));

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Cập nhật user thành công'
        ]);
    }

    /**
     * Xóa user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa chính mình'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa user thành công'
        ]);
    }

    /**
     * Lock user
     */
    public function lockUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_locked' => true, 'locked_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'User đã bị khóa'
        ]);
    }

    /**
     * Unlock user
     */
    public function unlockUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_locked' => false, 'locked_at' => null]);

        return response()->json([
            'success' => true,
            'message' => 'User đã được mở khóa'
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min=8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reset password thành công'
        ]);
    }

    /**
     * User activity
     */
    public function userActivity($id)
    {
        $activity = $this->userService->getUserActivity($id);

        return response()->json([
            'success' => true,
            'data' => $activity
        ]);
    }

    /**
     * Bulk operation
     */
    public function bulkOperation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'operation' => 'required|in:activate,deactivate,lock,unlock,delete',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->userService->bulkOperation($request->user_ids, $request->operation);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => "Bulk operation completed: {$result['success']} success, {$result['failed']} failed"
        ]);
    }

    /**
     * Import users
     */
    public function importUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->userService->importUsers($request->file('file'));

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => "Imported {$result['imported']} users"
        ]);
    }

    /**
     * Export users
     */
    public function exportUsers(Request $request)
    {
        $format = $request->format ?? 'csv';
        $export = $this->userService->exportUsers($format);

        return response()->json([
            'success' => true,
            'data' => $export
        ]);
    }
}