<?php

namespace App\Http\Controllers\Api\V1\Module19_MobileSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SecureContainerService;

class SecureContainerController extends Controller
{
    protected $containerService;

    public function __construct(SecureContainerService $containerService)
    {
        $this->containerService = $containerService;
    }

    /**
     * Tạo secure container
     */
    public function createContainer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'user_id' => 'required|string',
            'encryption_key' => 'nullable|string',
            'storage_limit_mb' => 'nullable|integer|min=10|max=1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $container = $this->containerService->createContainer($request->all());

        return response()->json([
            'success' => true,
            'data' => $container,
            'message' => 'Secure container đã được tạo'
        ]);
    }

    /**
     * Container status
     */
    public function containerStatus($containerId)
    {
        $status = $this->containerService->getContainerStatus($containerId);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Encrypt data in container
     */
    public function encryptData(Request $request, $containerId)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|string',
            'filename' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->containerService->encryptData(
            $containerId,
            $request->data,
            $request->filename
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Dữ liệu đã được mã hóa và lưu vào container'
        ]);
    }

    /**
     * Decrypt data from container
     */
    public function decryptData(Request $request, $containerId)
    {
        $validator = Validator::make($request->all(), [
            'file_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $this->containerService->decryptData($containerId, $request->file_id);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Delete container
     */
    public function deleteContainer($containerId)
    {
        $result = $this->containerService->deleteContainer($containerId);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Container đã được xóa' : 'Xóa container thất bại'
        ]);
    }

    /**
     * Wipe container
     */
    public function wipeContainer($containerId)
    {
        $result = $this->containerService->wipeContainer($containerId);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Container đã được wipe' : 'Wipe thất bại'
        ]);
    }

    /**
     * Container files
     */
    public function containerFiles($containerId)
    {
        $files = $this->containerService->getContainerFiles($containerId);

        return response()->json([
            'success' => true,
            'data' => $files
        ]);
    }
}