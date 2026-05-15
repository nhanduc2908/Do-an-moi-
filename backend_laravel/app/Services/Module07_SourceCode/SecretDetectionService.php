<?php

namespace App\Services\Module07_SourceCode;

use App\Models\Module07_SourceCode\SecretDetected;

class SecretDetectionService
{
    protected $patterns = [
        'aws_key' => '/AKIA[0-9A-Z]{16}/',
        'aws_secret' => '/[A-Za-z0-9/+=]{40}/',
        'private_key' => '/-----BEGIN (RSA|DSA|EC|OPENSSH) PRIVATE KEY-----/',
        'github_token' => '/ghp_[A-Za-z0-9]{36}/',
        'api_key' => '/[a-zA-Z0-9]{32,}/',
        'jwt_token' => '/eyJ[A-Za-z0-9_-]{10,}\.[A-Za-z0-9_-]{10,}\.[A-Za-z0-9_-]{10,}/'
    ];

    public function scanFile($filePath, $codeScanResultId)
    {
        $content = file_get_contents($filePath);
        $secrets = [];

        foreach ($this->patterns as $type => $pattern) {
            if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[0] as $match) {
                    $secret = SecretDetected::create([
                        'code_scan_result_id' => $codeScanResultId,
                        'file_path' => $filePath,
                        'line_number' => $this->getLineNumber($content, $match[1]),
                        'secret_type' => $type,
                        'secret_value_hash' => hash('sha256', $match[0]),
                        'is_valid' => $this->validateSecret($match[0], $type),
                        'is_revoked' => false
                    ]);
                    
                    $secrets[] = $secret;
                }
            }
        }

        return $secrets;
    }

    protected function validateSecret($secret, $type)
    {
        // Additional validation logic
        return true;
    }

    protected function getLineNumber($content, $offset)
    {
        return substr_count(substr($content, 0, $offset), "\n") + 1;
    }
}