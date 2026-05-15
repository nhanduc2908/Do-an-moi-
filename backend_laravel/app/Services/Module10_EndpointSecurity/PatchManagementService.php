<?php

namespace App\Services\Module10_EndpointSecurity;

use App\Models\Module10_EndpointSecurity\PatchStatus;
use Illuminate\Support\Facades\Http;

class PatchManagementService
{
    protected $vulnerabilityDatabase = 'https://services.nvd.nist.gov/rest/json/cves/2.0';

    public function scanMissingPatches($endpointId, $installedSoftware)
    {
        $missingPatches = [];
        
        foreach ($installedSoftware as $software) {
            $vulnerabilities = $this->checkVulnerabilities($software['name'], $software['version']);
            
            foreach ($vulnerabilities as $vuln) {
                if (isset($vuln['patch_available'])) {
                    $missingPatches[] = [
                        'patch_name' => $vuln['patch_name'],
                        'software' => $software['name'],
                        'current_version' => $software['version'],
                        'required_version' => $vuln['fixed_version'],
                        'severity' => $vuln['severity'],
                        'cve_id' => $vuln['cve_id']
                    ];
                }
            }
        }
        
        foreach ($missingPatches as $patch) {
            PatchStatus::updateOrCreate(
                [
                    'endpoint_id' => $endpointId,
                    'patch_name' => $patch['patch_name']
                ],
                [
                    'software_name' => $patch['software'],
                    'installed_version' => $patch['current_version'],
                    'required_version' => $patch['required_version'],
                    'status' => 'missing',
                    'cve_id' => $patch['cve_id']
                ]
            );
        }
        
        return $missingPatches;
    }

    protected function checkVulnerabilities($software, $version)
    {
        // Query vulnerability database
        $response = Http::get($this->vulnerabilityDatabase, [
            'keywordSearch' => $software,
            'resultsPerPage' => 20
        ]);
        
        $vulnerabilities = [];
        if ($response->successful()) {
            $data = $response->json();
            foreach ($data['vulnerabilities'] as $vuln) {
                $cve = $vuln['cve'];
                if ($this->isAffected($cve, $software, $version)) {
                    $vulnerabilities[] = [
                        'cve_id' => $cve['id'],
                        'severity' => $cve['metrics']['cvssMetricV31'][0]['cvssData']['baseSeverity'],
                        'patch_name' => $cve['id'] . '_patch',
                        'fixed_version' => $this->getFixedVersion($cve, $software)
                    ];
                }
            }
        }
        
        return $vulnerabilities;
    }

    protected function isAffected($cve, $software, $version)
    {
        $configurations = $cve['configurations'] ?? [];
        foreach ($configurations as $config) {
            foreach ($config['nodes'] as $node) {
                foreach ($node['cpeMatch'] as $cpe) {
                    if (str_contains($cpe['criteria'], $software)) {
                        if ($this->versionInRange($version, $cpe['versionStartIncluding'], $cpe['versionEndExcluding'])) {
                            return true;
                        }
                    }
                }
            }
        }
        
        return false;
    }

    protected function versionInRange($version, $start, $end)
    {
        if ($start && version_compare($version, $start, '<')) return false;
        if ($end && version_compare($version, $end, '>=')) return false;
        return true;
    }

    protected function getFixedVersion($cve, $software)
    {
        // Extract fixed version from CVE data
        return 'latest';
    }

    public function deployPatch($patchId, $endpointId)
    {
        $patch = PatchStatus::findOrFail($patchId);
        
        // Deploy patch logic
        $patch->status = 'deploying';
        $patch->save();
        
        // Simulate patch deployment
        sleep(5);
        
        $patch->status = 'installed';
        $patch->installed_at = now();
        $patch->save();
        
        return $patch;
    }
}