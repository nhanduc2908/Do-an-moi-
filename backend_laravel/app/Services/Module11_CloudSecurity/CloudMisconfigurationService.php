<?php

namespace App\Services\Module11_CloudSecurity;

use App\Models\Module11_CloudSecurity\CloudConfigIssue;

class CloudMisconfigurationService
{
    public function scanAllProviders()
    {
        $awsService = new AwsSecurityService();
        $azureService = new AzureSecurityService();
        $gcpService = new GcpSecurityService();
        
        $allIssues = array_merge(
            $awsService->scanS3Buckets(),
            $awsService->scanIamRoles(),
            $azureService->scanStorageAccounts(),
            $azureService->scanKeyVaults(),
            $gcpService->scanCloudStorage(),
            $gcpService->scanIamPolicies()
        );
        
        foreach ($allIssues as $issue) {
            CloudConfigIssue::updateOrCreate(
                [
                    'resource_id' => $issue['resource'],
                    'issue_type' => $issue['type']
                ],
                [
                    'severity' => $issue['severity'],
                    'description' => $issue['description'],
                    'is_fixed' => false
                ]
            );
        }
        
        return $allIssues;
    }

    public function getRemediationSteps($issueType)
    {
        $remediations = [
            'public_bucket' => [
                'step1' => 'Remove public access from bucket ACL',
                'step2' => 'Update bucket policy to block public access',
                'step3' => 'Enable "Block all public access" setting'
            ],
            'unencrypted_bucket' => [
                'step1' => 'Enable default encryption on bucket',
                'step2' => 'Use AWS KMS or SSE-S3 for encryption',
                'step3' => 'Re-encrypt existing objects'
            ],
            'over_permissive_role' => [
                'step1' => 'Review role permissions',
                'step2' => 'Apply least privilege principle',
                'step3' => 'Use specific actions instead of wildcard'
            ]
        ];
        
        return $remediations[$issueType] ?? ['step1' => 'Review cloud documentation for remediation'];
    }
}