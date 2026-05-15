<?php

namespace App\Services\Module07_SourceCode;

use Illuminate\Support\Facades\Http;

class DependencyScannerService
{
    protected $npmRegistry = 'https://registry.npmjs.org/';
    protected $packagistApi = 'https://repo.packagist.org/';

    public function scanComposerJson($composerPath)
    {
        $composer = json_decode(file_get_contents($composerPath), true);
        $dependencies = array_merge(
            $composer['require'] ?? [],
            $composer['require-dev'] ?? []
        );

        $vulnerabilities = [];
        
        foreach ($dependencies as $package => $version) {
            $vuln = $this->checkVulnerability($package, $version);
            if ($vuln) {
                $vulnerabilities[] = $vuln;
            }
        }

        return $vulnerabilities;
    }

    public function scanPackageJson($packagePath)
    {
        $package = json_decode(file_get_contents($packagePath), true);
        $dependencies = array_merge(
            $package['dependencies'] ?? [],
            $package['devDependencies'] ?? []
        );

        $vulnerabilities = [];
        
        foreach ($dependencies as $package => $version) {
            $vuln = $this->checkNpmVulnerability($package, $version);
            if ($vuln) {
                $vulnerabilities[] = $vuln;
            }
        }

        return $vulnerabilities;
    }

    protected function checkVulnerability($package, $version)
    {
        // Check against vulnerability database (NVD, Snyk, etc.)
        return null;
    }

    protected function checkNpmVulnerability($package, $version)
    {
        $response = Http::get("{$this->npmRegistry}{$package}/{$version}");
        
        if ($response->successful()) {
            $data = $response->json();
            return $data['vulnerabilities'] ?? null;
        }
        
        return null;
    }
}