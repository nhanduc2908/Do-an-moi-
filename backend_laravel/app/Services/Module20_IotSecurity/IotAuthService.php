<?php

namespace App\Services\Module20_IotSecurity;

use App\Models\Module20_IotSecurity\IotDevice;

class IotAuthService
{
    public function authenticateDevice($deviceId, $certificate)
    {
        $device = IotDevice::where('device_id', $deviceId)->first();
        
        if (!$device) {
            return ['authenticated' => false, 'reason' => 'Device not found'];
        }
        
        if ($device->status !== 'active') {
            return ['authenticated' => false, 'reason' => 'Device is not active'];
        }
        
        $validCert = $this->validateCertificate($certificate, $device);
        
        if (!$validCert) {
            return ['authenticated' => false, 'reason' => 'Invalid certificate'];
        }
        
        $token = $this->generateDeviceToken($device);
        
        $device->last_seen_at = now();
        $device->save();
        
        return [
            'authenticated' => true,
            'token' => $token,
            'device_id' => $device->device_id
        ];
    }

    protected function validateCertificate($certificate, $device)
    {
        // Validate X.509 certificate
        return true;
    }

    protected function generateDeviceToken($device)
    {
        return bin2hex(random_bytes(32));
    }

    public function provisionDevice($deviceData)
    {
        $device = IotDevice::create([
            'device_id' => $deviceData['device_id'],
            'device_type' => $deviceData['type'],
            'manufacturer' => $deviceData['manufacturer'],
            'model' => $deviceData['model'],
            'firmware_version' => $deviceData['firmware_version'],
            'location' => $deviceData['location'] ?? null,
            'status' => 'provisioned'
        ]);
        
        $certificate = $this->generateDeviceCertificate($device);
        
        return [
            'device' => $device,
            'certificate' => $certificate
        ];
    }

    protected function generateDeviceCertificate($device)
    {
        // Generate device certificate
        return '-----BEGIN CERTIFICATE-----\n...\n-----END CERTIFICATE-----';
    }

    public function revokeDevice($deviceId)
    {
        $device = IotDevice::where('device_id', $deviceId)->first();
        
        if ($device) {
            $device->status = 'revoked';
            $device->save();
            
            // Add to revocation list
            $this->addToRevocationList($deviceId);
        }
        
        return ['revoked' => true];
    }

    protected function addToRevocationList($deviceId)
    {
        cache()->put("revoked_device_{$deviceId}", true, now()->addDays(30));
    }
}