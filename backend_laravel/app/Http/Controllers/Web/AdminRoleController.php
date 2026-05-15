<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class AdminRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin');
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
            ->paginate(15);

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Form tạo role
     */
    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->get();
        $groupedPermissions = $permissions->groupBy('module');

        return view('admin.roles.create', compact('groupedPermissions'));
    }

    /**
     * Lưu role mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles|max:100',
            'level' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'level' => $request->level,
            'description' => $request->description,
            'is_active' => true,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Chi tiết role
     */
    public function show($id)
    {
        $role = Role::with('permissions', 'users')->findOrFail($id);

        return view('admin.roles.show', compact('role'));
    }

    /**
     * Form sửa role
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('module')->orderBy('name')->get();
        $groupedPermissions = $permissions->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    /**
     * Cập nhật role
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'level' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role->update([
            'name' => $request->name,
            'level' => $request->level,
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Xóa role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with associated users.');
        }

        if ($role->name === 'super_admin') {
            return back()->with('error', 'Cannot delete Super Admin role.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}