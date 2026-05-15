<?php

namespace App\Http\Controllers\Api\V1\Module28_SystemAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Services\RoleManagementService;

class RoleManagementController extends Controller
{
    protected $roleService;

    public function __construct(RoleManagementService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Danh sách roles
     */
    public function index(Request $request)
    {
        $roles = Role::withCount('users')
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('level', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * Chi tiết role
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    /**
     * Tạo role
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles|max=100',
            'level' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $role = $this->roleService->createRole($request->all());

        return response()->json([
            'success' => true,
            'data' => $role,
            'message' => 'Tạo role thành công'
        ]);
    }

    /**
     * Cập nhật role
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|unique:roles,name,' . $id,
            'level' => 'sometimes|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $role->update($request->only(['name', 'level', 'description']));

        return response()->json([
            'success' => true,
            'data' => $role,
            'message' => 'Cập nhật role thành công'
        ]);
    }

    /**
     * Xóa role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa role đang có người dùng'
            ], 400);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa role thành công'
        ]);
    }

    /**
     * Assign permissions
     */
    public function assignPermissions(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $role = Role::findOrFail($id);
        $role->permissions()->sync($request->permissions);

        return response()->json([
            'success' => true,
            'message' => 'Gán permissions thành công'
        ]);
    }

    /**
     * Get permissions
     */
    public function getPermissions($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $role->permissions
        ]);
    }

    /**
     * Clone role
     */
    public function cloneRole($id)
    {
        $role = Role::findOrFail($id);
        $newRole = $this->roleService->cloneRole($role);

        return response()->json([
            'success' => true,
            'data' => $newRole,
            'message' => 'Clone role thành công'
        ]);
    }
}