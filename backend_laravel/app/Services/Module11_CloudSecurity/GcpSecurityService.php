<?php

namespace App\Services\Module11_CloudSecurity;

use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Iam\IamClient;

class GcpSecurityService
{
    protected $storageClient;
    protected $iamClient;

    public function __construct()
    {
        $this->storageClient = new StorageClient([
            'keyFilePath' => config('services.gcp.key_file')
        ]);
        
        $this->iamClient = new IamClient([
            'keyFilePath' => config('services.gcp.key_file')
        ]);
    }

    public function scanCloudStorage()
    {
        $buckets = $this->storageClient->buckets();
        $issues = [];
        
        foreach ($buckets as $bucket) {
            // Check bucket IAM policy
            $policy = $bucket->iam()->policy();
            
            foreach ($policy['bindings'] as $binding) {
                if (in_array('allUsers', $binding['members']) || in_array('allAuthenticatedUsers', $binding['members'])) {
                    $issues[] = [
                        'resource' => $bucket->name(),
                        'type' => 'public_bucket',
                        'severity' => 'critical',
                        'description' => 'Bucket has public access'
                    ];
                }
            }
            
            // Check bucket versioning
            $info = $bucket->info();
            if (!isset($info['versioning']['enabled']) || !$info['versioning']['enabled']) {
                $issues[] = [
                    'resource' => $bucket->name(),
                    'type' => 'versioning_disabled',
                    'severity' => 'medium',
                    'description' => 'Bucket versioning is disabled'
                ];
            }
        }
        
        return $issues;
    }

    public function scanIamPolicies()
    {
        $project = config('services.gcp.project_id');
        $policies = $this->iamClient->getProjectIamPolicy($project);
        $issues = [];
        
        foreach ($policies['bindings'] as $binding) {
            // Check for primitive roles
            $primitiveRoles = ['roles/owner', 'roles/editor', 'roles/viewer'];
            if (in_array($binding['role'], $primitiveRoles)) {
                $issues[] = [
                    'resource' => $binding['role'],
                    'type' => 'primitive_role',
                    'severity' => 'high',
                    'description' => 'Primitive role is assigned, use predefined roles instead'
                ];
            }
        }
        
        return $issues;
    }
}