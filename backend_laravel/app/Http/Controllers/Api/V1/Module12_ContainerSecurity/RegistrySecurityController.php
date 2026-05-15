<?php

namespace App\Http\Controllers\Api\V1\Module12_ContainerSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\RegistrySecurityService;

class RegistrySecurityController extends Controller
{
    protected $registryService;

    public function __construct(RegistrySecurityService $registryService)
    {
        $this->registryService = $registryService;
    }

    /**
     * Quét registry
     */
    public function scanRegistry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registry_url' => 'required|url',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $scanId = $this->registryService->scanRegistry($request->all());

        return response()->json([
            'success' => true,
            'data' => ['scan_id' => $scanId],
            'message' => 'Bắt đầu quét registry'
        ]);
    }

    /**
     * Cleanup old images
     */
    public function cleanupImages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days_old' => 'required|integer|min=1|max=365',
            'dry_run' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->registryService->cleanupOldImages(
            $request->days_old,
            $request->dry_run ?? true
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Image signing
     */
    public function signImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_name' => 'required|string',
            'tag' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->registryService->signImage(
            $request->image_name,
            $request->tag
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Đã ký image' : 'Không thể ký image'
        ]);
    }

    /**
     * Verify image signature
     */
    public function verifySignature(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_name' => 'required|string',
            'tag' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->registryService->verifySignature(
            $request->image_name,
            $request->tag
        );

        return response()->json([
            'success' => true,
            'data' => ['is_valid' => $result]
        ]);
    }
}