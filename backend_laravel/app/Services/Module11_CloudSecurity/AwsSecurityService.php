<?php

namespace App\Services\Module11_CloudSecurity;

use Aws\S3\S3Client;
use Aws\IAM\IamClient;
use Aws\EC2\Ec2Client;
use Aws\CloudTrail\CloudTrailClient;
use App\Models\Module11_CloudSecurity\CloudResource;

class AwsSecurityService
{
    protected $s3Client;
    protected $iamClient;
    protected $ec2Client;
    protected $cloudTrailClient;

    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => config('services.aws.region'),
            'credentials' => [
                'key' => config('services.aws.key'),
                'secret' => config('services.aws.secret')
            ]
        ]);
        
        $this->iamClient = new IamClient([
            'version' => 'latest',
            'region' => config('services.aws.region'),
            'credentials' => [
                'key' => config('services.aws.key'),
                'secret' => config('services.aws.secret')
            ]
        ]);
    }

    public function scanS3Buckets()
    {
        $buckets = $this->s3Client->listBuckets();
        $issues = [];
        
        foreach ($buckets['Buckets'] as $bucket) {
            $bucketName = $bucket['Name'];
            
            // Check bucket ACL
            $acl = $this->s3Client->getBucketAcl(['Bucket' => $bucketName]);
            foreach ($acl['Grants'] as $grant) {
                if (isset($grant['Grantee']['URI']) && 
                    $grant['Grantee']['URI'] === 'http://acs.amazonaws.com/groups/global/AllUsers') {
                    $issues[] = [
                        'resource' => $bucketName,
                        'type' => 'public_bucket',
                        'severity' => 'critical',
                        'description' => 'Bucket has public access'
                    ];
                }
            }
            
            // Check bucket encryption
            try {
                $encryption = $this->s3Client->getBucketEncryption(['Bucket' => $bucketName]);
                if (!isset($encryption['ServerSideEncryptionConfiguration'])) {
                    $issues[] = [
                        'resource' => $bucketName,
                        'type' => 'unencrypted_bucket',
                        'severity' => 'high',
                        'description' => 'Bucket encryption is not enabled'
                    ];
                }
            } catch (\Exception $e) {
                $issues[] = [
                    'resource' => $bucketName,
                    'type' => 'unencrypted_bucket',
                    'severity' => 'high',
                    'description' => 'Bucket encryption is not configured'
                ];
            }
        }
        
        return $issues;
    }

    public function scanIamRoles()
    {
        $roles = $this->iamClient->listRoles();
        $issues = [];
        
        foreach ($roles['Roles'] as $role) {
            // Check for over-permissive roles
            $policy = $this->iamClient->getRolePolicy([
                'RoleName' => $role['RoleName'],
                'PolicyName' => $role['RoleName'] . '_policy'
            ]);
            
            if (isset($policy['PolicyDocument'])) {
                $policyDoc = json_decode($policy['PolicyDocument'], true);
                foreach ($policyDoc['Statement'] as $statement) {
                    if (isset($statement['Action']) && $statement['Action'] === '*') {
                        $issues[] = [
                            'resource' => $role['RoleName'],
                            'type' => 'over_permissive_role',
                            'severity' => 'critical',
                            'description' => 'Role has wildcard permissions'
                        ];
                    }
                }
            }
        }
        
        return $issues;
    }

    public function getComplianceReport()
    {
        return [
            'service' => 'AWS',
            'scan_time' => now(),
            'total_resources' => CloudResource::where('provider', 'aws')->count(),
            'issues' => array_merge(
                $this->scanS3Buckets(),
                $this->scanIamRoles()
            )
        ];
    }
}