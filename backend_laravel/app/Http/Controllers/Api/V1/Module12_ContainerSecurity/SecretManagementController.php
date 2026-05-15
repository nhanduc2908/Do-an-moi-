<?php

namespace App\Http\Controllers\Api\V1\Module12_ContainerSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SecretManagementService;

class SecretManagementController extends Controller
{
    protected $secretService;

    public function __construct(SecretManagementService $secretService)
    {
        $this->secretService = $secretService;
    }

    /**
     * Danh sách secrets
     */
    public function secrets(Request $request)
    {
        $secrets = $this->secretService->getSecrets();

        return response()->json([
            'success' => true,
            'data' => $secrets
        ]);
    }

    /**
     * Tạo secret
     */
    public function createSecret(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'value' => 'required|string',
            'type' => 'nullable|in:generic,tls,docker-registry',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $secret = $this->secretService->createSecret($request->all());

        return response()->json([
            'success' => true,
            'data' => $secret,
            'message' => 'Tạo secret thành công'
        ]);
    }

    /**
     * Xóa secret
     */
    public function deleteSecret($name)
    {
        $result = $this->secretService->deleteSecret($name);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa secret thành công' : 'Xóa thất bại'
        ]);
    }

    /**
     * Scan for exposed secrets
     */
    public function scanExposedSecrets()
    {
        $exposed = $this->secretService->scanExposedSecrets();

        return response()->json([
            'success' => true,
            'data' => $exposed
        ]);
    }

    /**
     * Rotate secret
     */
    public function rotateSecret(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->secretService->rotateSecret($request->name);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Secret đã được xoay vòng' : 'Không thể xoay vòng secret'
        ]);
    }
}