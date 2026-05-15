<?php

namespace App\Http\Controllers\Api\V1\Module01_IAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Events\RoleChangedEvent;

class RoleController extends Controller
{
    /**
     * Danh sách roles
     */
    public function index(Request $request)
    {
        $roles = Role::withCount('users')
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * Tạo role mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles|max:100',
            'level' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $role = Role::create([
            'name' => $request->name,
            'level' => $request->level,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $role,
            'message' => 'Tạo role thành công'
        ], 201);
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
     * Cập nhật role
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|unique:roles,name,' . $id . '|max:100',
            'level' => 'sometimes|integer|min:1|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $role->update($request->only(['name', 'level', 'description', 'is_active']));

        event(new RoleChangedEvent($role->id, $role->name, 'updated', auth()->id()));

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
     * Gán permissions cho role
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
}