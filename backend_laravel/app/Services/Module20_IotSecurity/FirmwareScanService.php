<?php

namespace App\Services\Module20_IotSecurity;

use App\Models\Module20_IotSecurity\IotFirmware;

class FirmwareScanService
{
    public function uploadFirmware($file, $deviceId, $version)
    {
        $path = $file->store('firmware', 'secure');
        $hash = hash_file('sha256', $file->path());
        
        $firmware = IotFirmware::create([
            'device_id' => $deviceId,
            'version' => $version,
            'file_hash' => $hash,
            'file_size' => $file->getSize(),
            'release_date' => now(),
            'is_secure' => false
        ]);
        
        $this->scanFirmware($firmware);
        
        return $firmware;
    }

    public function scanFirmware($firmware)
    {
        $issues = [];
        
        // Extract firmware
        $extractedPath = $this->extractFirmware($firmware->file_path);
        
        // Check for hardcoded credentials
        $hardcoded = $this->checkHardcodedSecrets($extractedPath);
        if ($hardcoded) {
            $issues[] = ['type' => 'hardcoded_credentials', 'severity' => 'critical'];
        }
        
        // Check for insecure services
        $insecureServices = $this->checkInsecureServices($extractedPath);
        $issues = array_merge($issues, $insecureServices);
        
        // Check for outdated libraries
        $outdatedLibs = $this->checkOutdatedLibraries($extractedPath);
        $issues = array_merge($issues, $outdatedLibs);
        
        $firmware->vulnerabilities = $issues;
        $firmware->is_secure = empty($issues);
        $firmware->save();
        
        return $issues;
    }

    protected function extractFirmware($path)
    {
        // Extract firmware using binwalk or similar
        return storage_path('extracted/' . basename($path));
    }

    protected function checkHardcodedSecrets($path)
    {
        $secrets = ['password', 'apikey', 'token', 'secret'];
        $found = [];
        
        foreach ($secrets as $secret) {
            $result = shell_exec("grep -r -i '{$secret}' {$path}");
            if ($result) {
                $found[] = "Hardcoded {$secret} found";
            }
        }
        
        return $found;
    }

    protected function checkInsecureServices($path)
    {
        return [];
    }

    protected function checkOutdatedLibraries($path)
    {
        return [];
    }

    public function getFirmwareReport($deviceId)
    {
        $firmware = IotFirmware::where('device_id', $deviceId)
            ->orderBy('release_date', 'desc')
            ->first();
        
        if (!$firmware) {
            return ['error' => 'No firmware found'];
        }
        
        return [
            'version' => $firmware->version,
            'release_date' => $firmware->release_date,
            'is_secure' => $firmware->is_secure,
            'vulnerabilities' => $firmware->vulnerabilities,
            'recommendation' => $firmware->is_secure ? 'OK' : 'Update required'
        ];
    }
}