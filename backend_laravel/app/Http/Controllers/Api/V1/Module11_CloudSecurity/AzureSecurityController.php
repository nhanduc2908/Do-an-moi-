<?php

namespace App\Http\Controllers\Api\V1\Module11_CloudSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AzureSecurityService;

class AzureSecurityController extends Controller
{
    protected $azureService;

    public function __construct(AzureSecurityService $azureService)
    {
        $this->azureService = $azureService;
    }

    /**
     * Cấu hình Azure
     */
    public function configure(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|string',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'subscription_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->azureService->configure($request->all());

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Cấu hình Azure thành công' : 'Cấu hình thất bại'
        ]);
    }

    /**
     * Security Center scan
     */
    public function securityCenter(Request $request)
    {
        $result = $this->azureService->getSecurityCenterStatus();

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Network security groups
     */
    public function networkSecurityGroups()
    {
        $nsgs = $this->azureService->getNetworkSecurityGroups();

        return response()->json([
            'success' => true,
            'data' => $nsgs
        ]);
    }

    /**
     * Storage security
     */
    public function storageSecurity()
    {
        $storage = $this->azureService->checkStorageSecurity();

        return response()->json([
            'success' => true,
            'data' => $storage
        ]);
    }

    /**
     * Key vault status
     */
    public function keyVaultStatus()
    {
        $vaults = $this->azureService->getKeyVaultStatus();

        return response()->json([
            'success' => true,
            'data' => $vaults
        ]);
    }

    /**
     * Azure AD security
     */
    public function adSecurity()
    {
        $ad = $this->azureService->checkAzureAdSecurity();

        return response()->json([
            'success' => true,
            'data' => $ad
        ]);
    }

    /**
     * Security recommendations
     */
    public function recommendations()
    {
        $recommendations = $this->azureService->getSecurityRecommendations();

        return response()->json([
            'success' => true,
            'data' => $recommendations
        ]);
    }
}