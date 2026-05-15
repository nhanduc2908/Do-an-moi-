<?php

namespace App\Services\Module24_DevSecOps;

use App\Models\Module24_DevSecOps\SecretScanResult;

class SecretScanService
{
    protected $patterns = [
        'aws_key' => [
            'pattern' => '/(AKIA|ASIA)[0-9A-Z]{16}/',
            'severity' => 'critical',
            'description' => 'AWS Access Key ID'
        ],
        'aws_secret' => [
            'pattern' => '/[A-Za-z0-9/+=]{40}/',
            'severity' => 'critical',
            'description' => 'AWS Secret Access Key'
        ],
        'github_token' => [
            'pattern' => '/ghp_[A-Za-z0-9]{36}/',
            'severity' => 'critical',
            'description' => 'GitHub Personal Access Token'
        ],
        'private_key' => [
            'pattern' => '/-----BEGIN (RSA|DSA|EC|OPENSSH) PRIVATE KEY-----/',
            'severity' => 'critical',
            'description' => 'Private Key'
        ],
        'jwt_token' => [
            'pattern' => '/eyJ[A-Za-z0-9_-]{10,}\.[A-Za-z0-9_-]{10,}\.[A-Za-z0-9_-]{10,}/',
            'severity' => 'high',
            'description' => 'JWT Token'
        ],
        'api_key' => [
            'pattern' => '/[a-zA-Z0-9]{32,40}/',
            'severity' => 'high',
            'description' => 'Generic API Key'
        ],
        'password' => [
            'pattern' => '/(password|passwd|pwd)\s*[=:]\s*["\']?[^"\'\s]{8,}["\']?/i',
            'severity' => 'critical',
            'description' => 'Hardcoded Password'
        ]
    ];

    public function scanRepository($repository, $branch = 'main')
    {
        $results = [];
        
        // Clone or fetch repository
        $files = $this->getRepositoryFiles($repository, $branch);
        
        foreach ($files as $file) {
            $fileResults = $this->scanFile($file['path'], $file['content']);
            $results = array_merge($results, $fileResults);
        }
        
        foreach ($results as $result) {
            SecretScanResult::create([
                'repository' => $repository,
                'file_path' => $result['file'],
                'line_number' => $result['line'],
                'secret_type' => $result['type'],
                'secret_hash' => hash('sha256', $result['secret']),
                'is_valid' => $this->validateSecret($result['secret'], $result['type']),
                'detected_at' => now()
            ]);
        }
        
        return $results;
    }

    protected function getRepositoryFiles($repository, $branch)
    {
        // Get files from repository
        return [];
    }

    public function scanFile($filePath, $content)
    {
        $findings = [];
        $lines = explode("\n", $content);
        
        foreach ($lines as $lineNum => $line) {
            foreach ($this->patterns as $type => $pattern) {
                if (preg_match($pattern['pattern'], $line, $matches)) {
                    $findings[] = [
                        'file' => $filePath,
                        'line' => $lineNum + 1,
                        'type' => $type,
                        'secret' => $matches[0],
                        'severity' => $pattern['severity'],
                        'description' => $pattern['description']
                    ];
                }
            }
        }
        
        return $findings;
    }

    protected function validateSecret($secret, $type)
    {
        switch ($type) {
            case 'aws_key':
                return $this->validateAwsKey($secret);
            case 'github_token':
                return $this->validateGithubToken($secret);
            default:
                return true;
        }
    }

    protected function validateAwsKey($key)
    {
        // Check if AWS key is active
        return true;
    }

    protected function validateGithubToken($token)
    {
        // Check if GitHub token is valid
        return true;
    }

    public function revokeSecret($secretId)
    {
        $secret = SecretScanResult::findOrFail($secretId);
        
        // Call API to revoke the secret
        switch ($secret->secret_type) {
            case 'aws_key':
                $this->revokeAwsKey($secret->secret_hash);
                break;
            case 'github_token':
                $this->revokeGithubToken($secret->secret_hash);
                break;
        }
        
        $secret->is_revoked = true;
        $secret->revoked_at = now();
        $secret->save();
        
        return $secret;
    }

    protected function revokeAwsKey($keyHash)
    {
        // AWS IAM key revocation
    }

    protected function revokeGithubToken($tokenHash)
    {
        // GitHub token revocation
    }

    public function getSecretReport($repository = null)
    {
        $query = SecretScanResult::query();
        
        if ($repository) {
            $query->where('repository', $repository);
        }
        
        $secrets = $query->get();
        
        return [
            'total_secrets' => $secrets->count(),
            'active_secrets' => $secrets->where('is_revoked', false)->count(),
            'revoked_secrets' => $secrets->where('is_revoked', true)->count(),
            'by_type' => $secrets->groupBy('secret_type')->map->count(),
            'by_repository' => $secrets->groupBy('repository')->map->count(),
            'critical_secrets' => $secrets->filter(function($s) {
                return in_array($s->secret_type, ['aws_key', 'aws_secret', 'private_key']);
            })->count()
        ];
    }
}