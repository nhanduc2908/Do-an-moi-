<?php

namespace App\Services\Module12_ContainerSecurity;

use App\Models\Module12_ContainerSecurity\ContainerImage;
use Illuminate\Support\Facades\Process;

class DockerScanService
{
    public function scanImage($imageName, $imageTag = 'latest')
    {
        $fullImage = "{$imageName}:{$imageTag}";
        
        // Pull image
        Process::run("docker pull {$fullImage}");
        
        // Run vulnerability scan using Trivy
        $scanResult = Process::run("trivy image --format json {$fullImage}");
        
        if ($scanResult->successful()) {
            $data = json_decode($scanResult->output(), true);
            
            $image = ContainerImage::create([
                'image_name' => $imageName,
                'image_tag' => $imageTag,
                'image_digest' => $data['ArtifactID'] ?? null,
                'size' => $data['Size'] ?? 0,
                'is_vulnerable' => count($data['Vulnerabilities'] ?? []) > 0
            ]);
            
            $vulnerabilities = [];
            foreach ($data['Vulnerabilities'] ?? [] as $vuln) {
                $vulnerabilities[] = [
                    'container_image_id' => $image->id,
                    'vulnerability_id' => $vuln['VulnerabilityID'],
                    'severity' => $vuln['Severity'],
                    'package_name' => $vuln['PkgName'],
                    'installed_version' => $vuln['InstalledVersion'],
                    'fixed_version' => $vuln['FixedVersion'] ?? null,
                    'description' => $vuln['Title'] ?? $vuln['Description']
                ];
            }
            
            return [
                'image' => $image,
                'vulnerabilities' => $vulnerabilities,
                'total_vulnerabilities' => count($vulnerabilities)
            ];
        }
        
        return null;
    }

    public function checkDockerfileSecurity($dockerfilePath)
    {
        $issues = [];
        $content = file_get_contents($dockerfilePath);
        
        // Check for root user
        if (preg_match('/USER root/', $content)) {
            $issues[] = [
                'type' => 'root_user',
                'severity' => 'high',
                'line' => $this->getLineNumber($content, 'USER root'),
                'recommendation' => 'Use non-root user'
            ];
        }
        
        // Check for latest tag
        if (preg_match('/FROM .*:latest/', $content)) {
            $issues[] = [
                'type' => 'latest_tag',
                'severity' => 'medium',
                'recommendation' => 'Use specific image version tags'
            ];
        }
        
        // Check for secrets in Dockerfile
        $secretPatterns = ['PASSWORD', 'SECRET', 'KEY', 'TOKEN'];
        foreach ($secretPatterns as $pattern) {
            if (preg_match("/ENV\s+{$pattern}=/i", $content)) {
                $issues[] = [
                    'type' => 'secret_in_dockerfile',
                    'severity' => 'critical',
                    'recommendation' => 'Use build secrets or docker secrets'
                ];
            }
        }
        
        return $issues;
    }

    protected function getLineNumber($content, $search)
    {
        $lines = explode("\n", $content);
        foreach ($lines as $i => $line) {
            if (str_contains($line, $search)) {
                return $i + 1;
            }
        }
        return 0;
    }
}