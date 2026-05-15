<?php

namespace App\Services\Module24_DevSecOps;

use App\Models\Module24_DevSecOps\Sbom;
use Illuminate\Support\Facades\Http;

class SbomService
{
    public function generateSbom($application, $version, $components)
    {
        $sbom = Sbom::create([
            'application_name' => $application,
            'version' => $version,
            'components' => $components,
            'dependencies' => $this->buildDependencyGraph($components),
            'vulnerabilities' => $this->checkVulnerabilities($components),
            'generated_by' => auth()->id(),
            'generated_at' => now(),
            'format' => 'spdx-json'
        ]);
        
        return $sbom;
    }

    protected function buildDependencyGraph($components)
    {
        $graph = [];
        
        foreach ($components as $component) {
            $graph[$component['name']] = [
                'version' => $component['version'],
                'dependencies' => $component['dependencies'] ?? []
            ];
        }
        
        return $graph;
    }

    protected function checkVulnerabilities($components)
    {
        $vulnerabilities = [];
        
        foreach ($components as $component) {
            $response = Http::get('https://ossindex.sonatype.org/api/v3/component-report', [
                'coordinates' => "{$component['type']}:{$component['name']}:{$component['version']}"
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['vulnerabilities'])) {
                    $vulnerabilities[$component['name']] = $data['vulnerabilities'];
                }
            }
        }
        
        return $vulnerabilities;
    }

    public function compareSbom($sbomId1, $sbomId2)
    {
        $sbom1 = Sbom::findOrFail($sbomId1);
        $sbom2 = Sbom::findOrFail($sbomId2);
        
        $components1 = collect($sbom1->components);
        $components2 = collect($sbom2->components);
        
        return [
            'added' => $components2->keys()->diff($components1->keys())->values(),
            'removed' => $components1->keys()->diff($components2->keys())->values(),
            'version_changes' => $this->getVersionChanges($components1, $components2),
            'new_vulnerabilities' => $this->getNewVulnerabilities($sbom1, $sbom2)
        ];
    }

    protected function getVersionChanges($components1, $components2)
    {
        $changes = [];
        
        foreach ($components1 as $name => $component1) {
            if ($components2->has($name) && $components2[$name]['version'] !== $component1['version']) {
                $changes[$name] = [
                    'old' => $component1['version'],
                    'new' => $components2[$name]['version']
                ];
            }
        }
        
        return $changes;
    }

    protected function getNewVulnerabilities($sbom1, $sbom2)
    {
        $newVulns = [];
        $oldVulns = collect($sbom1->vulnerabilities);
        
        foreach ($sbom2->vulnerabilities as $component => $vulns) {
            $oldComponentVulns = $oldVulns->get($component, []);
            $newComponentVulns = collect($vulns);
            
            $added = $newComponentVulns->keys()->diff(collect($oldComponentVulns)->keys());
            
            if ($added->isNotEmpty()) {
                $newVulns[$component] = $added->values();
            }
        }
        
        return $newVulns;
    }

    public function exportSbom($sbomId, $format = 'json')
    {
        $sbom = Sbom::findOrFail($sbomId);
        
        switch ($format) {
            case 'json':
                return response()->json($sbom);
            case 'xml':
                return $this->convertToXml($sbom);
            case 'cyclonedx':
                return $this->convertToCycloneDx($sbom);
            default:
                return $sbom;
        }
    }

    protected function convertToXml($sbom)
    {
        // Convert to SPDX XML format
        return '';
    }

    protected function convertToCycloneDx($sbom)
    {
        // Convert to CycloneDX format
        return '';
    }

    public function getLicenseCompliance($sbomId)
    {
        $sbom = Sbom::findOrFail($sbomId);
        $licenseIssues = [];
        
        foreach ($sbom->components as $component) {
            $license = $component['license'] ?? 'unknown';
            
            if ($this->isRestrictedLicense($license)) {
                $licenseIssues[] = [
                    'component' => $component['name'],
                    'license' => $license,
                    'version' => $component['version'],
                    'risk' => $this->getLicenseRisk($license)
                ];
            }
        }
        
        return [
            'total_components' => count($sbom->components),
            'license_issues' => $licenseIssues,
            'compliance_status' => empty($licenseIssues) ? 'compliant' : 'non_compliant'
        ];
    }

    protected function isRestrictedLicense($license)
    {
        $restricted = ['GPL', 'AGPL', 'LGPL', 'MPL', 'EPL'];
        
        foreach ($restricted as $r) {
            if (strpos($license, $r) !== false) {
                return true;
            }
        }
        
        return false;
    }

    protected function getLicenseRisk($license)
    {
        $risks = [
            'GPL' => 'high',
            'AGPL' => 'critical',
            'LGPL' => 'medium',
            'MPL' => 'medium',
            'EPL' => 'low'
        ];
        
        foreach ($risks as $key => $risk) {
            if (strpos($license, $key) !== false) {
                return $risk;
            }
        }
        
        return 'unknown';
    }
}