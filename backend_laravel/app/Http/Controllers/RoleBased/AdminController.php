<?php

namespace App\Http\Controllers\RoleBased;

use App\Http\Controllers\Controller;
use App\Services\RoleBased\AdminService;
use App\Models\Module01_IAM\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $data = $this->adminService->getDashboardData();
        return view('admin.roles.admin.index', $data);
    }

    public function userManagement(Request $request)
    {
        $data = $this->adminService->getUserManagementData($request);
        return view('admin.roles.admin.user-management', $data);
    }

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->adminService->createUser($request->all());
        return response()->json($result);
    }

    public function updateUser(Request $request, $id)
    {
        $result = $this->adminService->updateUser($id, $request->all());
        return response()->json($result);
    }

    public function deleteUser($id)
    {
        $result = $this->adminService->deleteUser($id);
        return response()->json($result);
    }

    public function getUser($id)
    {
        $user = User::with('roles')->find($id);
        return response()->json(['success' => true, 'user' => $user]);
    }

    public function roleManagement()
    {
        $data = $this->adminService->getRoleManagementData();
        return view('admin.roles.admin.role-management', $data);
    }

    public function createRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name|regex:/^[a-z_]+$/',
            'display_name' => 'required|string|max:100',
            'level' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->adminService->createRole($request->all());
        return response()->json($result);
    }

    public function updateRole(Request $request, $id)
    {
        $result = $this->adminService->updateRole($id, $request->all());
        return response()->json($result);
    }

    public function deleteRole($id)
    {
        $result = $this->adminService->deleteRole($id);
        return response()->json($result);
    }

    public function assignPermissions(Request $request)
    {
        $result = $this->adminService->assignPermissionsToRole($request->role_id, $request->permissions);
        return response()->json($result);
    }

    public function getRolePermissions($id)
    {
        $result = $this->adminService->getRolePermissions($id);
        return response()->json($result);
    }

    public function getAllPermissions()
    {
        $permissions = \App\Models\Module01_IAM\Permission::all()->groupBy('module');
        return response()->json(['success' => true, 'permissions' => $permissions]);
    }

    public function systemConfig()
    {
        $data = $this->adminService->getSystemConfig();
        return view('admin.roles.admin.system-config', $data);
    }

    public function updateConfig(Request $request)
    {
        $result = $this->adminService->updateSystemConfig($request->all());
        return response()->json($result);
    }
}