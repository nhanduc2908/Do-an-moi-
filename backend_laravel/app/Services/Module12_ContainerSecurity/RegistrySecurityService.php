<?php

namespace App\Services\Module12_ContainerSecurity;

use App\Models\Module12_ContainerSecurity\RegistryImage;
use Illuminate\Support\Facades\Http;

class RegistrySecurityService
{
    public function scanRegistry($registryUrl)
    {
        $repositories = $this->listRepositories($registryUrl);
        $scanResults = [];
        
        foreach ($repositories as $repo) {
            $tags = $this->listTags($registryUrl, $repo);
            
            foreach ($tags as $tag) {
                $image = RegistryImage::updateOrCreate(
                    [
                        'registry_url' => $registryUrl,
                        'repository' => $repo,
                        'image_name' => $repo,
                        'tags' => $tag
                    ],
                    [
                        'size' => $this->getImageSize($registryUrl, $repo, $tag),
                        'scan_status' => 'pending'
                    ]
                );
                
                $scanResults[] = $image;
            }
        }
        
        return $scanResults;
    }

    protected function listRepositories($registryUrl)
    {
        $response = Http::get("{$registryUrl}/v2/_catalog");
        
        if ($response->successful()) {
            return $response->json()['repositories'] ?? [];
        }
        
        return [];
    }

    protected function listTags($registryUrl, $repository)
    {
        $response = Http::get("{$registryUrl}/v2/{$repository}/tags/list");
        
        if ($response->successful()) {
            return $response->json()['tags'] ?? [];
        }
        
        return [];
    }

    protected function getImageSize($registryUrl, $repository, $tag)
    {
        $response = Http::head("{$registryUrl}/v2/{$repository}/manifests/{$tag}");
        
        if ($response->successful()) {
            return $response->header('Content-Length') ?? 0;
        }
        
        return 0;
    }

    public function checkImageSigning($imageName)
    {
        // Check for Docker Content Trust
        $result = Process::run("docker trust inspect {$imageName} --format json");
        
        if ($result->successful()) {
            $data = json_decode($result->output(), true);
            return [
                'signed' => true,
                'signers' => $data['Signers'] ?? []
            ];
        }
        
        return ['signed' => false];
    }
}