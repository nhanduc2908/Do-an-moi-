<?php

namespace App\Http\Controllers\Api\V1\Module11_CloudSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AwsSecurityService;

class AwsSecurityController extends Controller
{
    protected $awsService;

    public function __construct(AwsSecurityService $awsService)
    {
        $this->awsService = $awsService;
    }

    /**
     * Cấu hình AWS connection
     */
    public function configure(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_key' => 'required|string',
            'secret_key' => 'required|string',
            'region' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->awsService->configure($request->all());

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Cấu hình AWS thành công' : 'Cấu hình thất bại'
        ]);
    }

    /**
     * Quét bảo mật AWS
     */
    public function scanSecurity(Request $request)
    {
        $scanId = $this->awsService->startSecurityScan();

        return response()->json([
            'success' => true,
            'data' => ['scan_id' => $scanId],
            'message' => 'Bắt đầu quét bảo mật AWS'
        ]);
    }

    /**
     * Kết quả quét
     */
    public function scanResult($scanId)
    {
        $result = $this->awsService->getScanResult($scanId);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * S3 bucket security
     */
    public function s3Security()
    {
        $buckets = $this->awsService->checkS3Security();

        return response()->json([
            'success' => true,
            'data' => $buckets
        ]);
    }

    /**
     * IAM security check
     */
    public function iamSecurity()
    {
        $iam = $this->awsService->checkIamSecurity();

        return response()->json([
            'success' => true,
            'data' => $iam
        ]);
    }

    /**
     * Security groups
     */
    public function securityGroups()
    {
        $groups = $this->awsService->getSecurityGroups();

        return response()->json([
            'success' => true,
            'data' => $groups
        ]);
    }

    /**
     * CloudTrail status
     */
    public function cloudTrailStatus()
    {
        $status = $this->awsService->getCloudTrailStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * GuardDuty findings
     */
    public function guardDutyFindings(Request $request)
    {
        $findings = $this->awsService->getGuardDutyFindings([
            'severity' => $request->severity,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $findings
        ]);
    }

    /**
     * AWS Config rules
     */
    public function configRules()
    {
        $rules = $this->awsService->getConfigRules();

        return response()->json([
            'success' => true,
            'data' => $rules
        ]);
    }
}