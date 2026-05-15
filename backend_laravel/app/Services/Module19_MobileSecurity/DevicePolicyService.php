<?php

namespace App\Services\Module19_MobileSecurity;

use App\Models\Module19_MobileSecurity\MobilePolicy;

class DevicePolicyService
{
    public function createPolicy($data)
    {
        return MobilePolicy::create([
            'policy_name' => $data['name'],
            'version' => $data['version'],
            'require_encryption' => $data['require_encryption'] ?? true,
            'require_pin' => $data['require_pin'] ?? true,
            'min_pin_length' => $data['min_pin_length'] ?? 6,
            'max_failed_attempts' => $data['max_failed_attempts'] ?? 10,
            'inactivity_timeout' => $data['inactivity_timeout'] ?? 15,
            'allow_camera' => $data['allow_camera'] ?? true,
            'allow_screenshot' => $data['allow_screenshot'] ?? true,
            'allow_usb_transfer' => $data['allow_usb_transfer'] ?? false,
            'is_active' => $data['is_active'] ?? false,
            'effective_date' => $data['effective_date'] ?? now()
        ]);
    }

    public function updatePolicy($policyId, $data)
    {
        $policy = MobilePolicy::findOrFail($policyId);
        $policy->update($data);
        
        // Re-apply policy to all devices if policy is active
        if ($policy->is_active) {
            $this->applyToAllDevices($policy);
        }
        
        return $policy;
    }

    public function activatePolicy($policyId)
    {
        // Deactivate all other policies
        MobilePolicy::query()->update(['is_active' => false]);
        
        $policy = MobilePolicy::findOrFail($policyId);
        $policy->is_active = true;
        $policy->save();
        
        $this->applyToAllDevices($policy);
        
        return $policy;
    }

    protected function applyToAllDevices($policy)
    {
        $mdmService = new MdmService();
        $devices = MobileDevice::where('status', 'active')->get();
        
        foreach ($devices as $device) {
            $mdmService->applyPolicies($device);
        }
    }

    public function getActivePolicy()
    {
        return MobilePolicy::where('is_active', true)->first();
    }

    public function getPolicyHistory()
    {
        return MobilePolicy::orderBy('created_at', 'desc')->get();
    }
}