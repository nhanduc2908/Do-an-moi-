<?php

namespace App\Http\Controllers\Api\V1\Module11_CloudSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\GcpSecurityService;

class GcpSecurityController extends Controller
{
    protected $gcpService;

    public function __construct(GcpSecurityService $gcpService)
    {
        $this->gcpService = $gcpService;
    }

    /**
     * Cấu hình GCP
     */
    public function configure(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|string',
            'credentials' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->gcpService->configure($request->all());

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Cấu hình GCP thành công' : 'Cấu hình thất bại'
        ]);
    }

    /**
     * Security Command Center
     */
    public function securityCommandCenter()
    {
        $findings = $this->gcpService->getSecurityFindings();

        return response()->json([
            'success' => true,
            'data' => $findings
        ]);
    }

    /**
     * IAM security
     */
    public function iamSecurity()
    {
        $iam = $this->gcpService->checkIamSecurity();

        return response()->json([
            'success' => true,
            'data' => $iam
        ]);
    }

    /**
     * Cloud Storage security
     */
    public function storageSecurity()
    {
        $buckets = $this->gcpService->checkStorageSecurity();

        return response()->json([
            'success' => true,
            'data' => $buckets
        ]);
    }

    /**
     * Cloud SQL security
     */
    public function sqlSecurity()
    {
        $sql = $this->gcpService->checkCloudSqlSecurity();

        return response()->json([
            'success' => true,
            'data' => $sql
        ]);
    }

    /**
     * Kubernetes security
     */
    public function kubernetesSecurity()
    {
        $gke = $this->gcpService->checkGkeSecurity();

        return response()->json([
            'success' => true,
            'data' => $gke
        ]);
    }

    /**
     * VPC security
     */
    public function vpcSecurity()
    {
        $vpc = $this->gcpService->checkVpcSecurity();

        return response()->json([
            'success' => true,
            'data' => $vpc
        ]);
    }
}