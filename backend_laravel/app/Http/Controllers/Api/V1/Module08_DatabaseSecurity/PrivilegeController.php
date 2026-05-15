<?php

namespace App\Http\Controllers\Api\V1\Module08_DatabaseSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DatabasePrivilegeService;

class PrivilegeController extends Controller
{
    protected $privilegeService;

    public function __construct(DatabasePrivilegeService $privilegeService)
    {
        $this->privilegeService = $privilegeService;
    }

    /**
     * Danh sách users
     */
    public function listUsers(Request $request)
    {
        $users = $this->privilegeService->getDatabaseUsers();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Chi tiết quyền của user
     */
    public function userPrivileges(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $privileges = $this->privilegeService->getUserPrivileges($request->username);

        return response()->json([
            'success' => true,
            'data' => $privileges
        ]);
    }

    /**
     * Cấp quyền
     */
    public function grantPrivilege(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'privilege' => 'required|string',
            'table' => 'nullable|string',
            'database' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->privilegeService->grantPrivilege(
            $request->username,
            $request->privilege,
            $request->database ?? '*',
            $request->table ?? '*'
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Cấp quyền thành công' : 'Cấp quyền thất bại'
        ]);
    }

    /**
     * Thu hồi quyền
     */
    public function revokePrivilege(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'privilege' => 'required|string',
            'table' => 'nullable|string',
            'database' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->privilegeService->revokePrivilege(
            $request->username,
            $request->privilege,
            $request->database ?? '*',
            $request->table ?? '*'
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Thu hồi quyền thành công' : 'Thu hồi quyền thất bại'
        ]);
    }

    /**
     * Phân tích privilege risks
     */
    public function analyzeRisks(Request $request)
    {
        $risks = $this->privilegeService->analyzePrivilegeRisks();

        return response()->json([
            'success' => true,
            'data' => $risks
        ]);
    }

    /**
     * Tạo user mới
     */
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:mysql_users',
            'password' => 'required|string|min:8',
            'host' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->privilegeService->createDatabaseUser(
            $request->username,
            $request->password,
            $request->host ?? '%'
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Tạo user thành công' : 'Tạo user thất bại'
        ]);
    }

    /**
     * Xóa user
     */
    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->privilegeService->deleteDatabaseUser($request->username);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa user thành công' : 'Xóa user thất bại'
        ]);
    }
}