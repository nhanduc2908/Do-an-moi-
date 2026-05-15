<?php

namespace App\Services\Module01_IAM;

use App\Models\Module01_IAM\Device;
use Illuminate\Http\Request;

class DeviceFingerprintService
{
    public function generateFingerprint($request)
    {
        $data = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'accept_language' => $request->header('Accept-Language'),
            'platform' => $this->getPlatform($request->userAgent())
        ];

        return hash('sha256', json_encode($data));
    }

    public function isTrustedDevice($user, $fingerprint)
    {
        return Device::where('user_id', $user->id)
            ->where('fingerprint', $fingerprint)
            ->where('is_trusted', true)
            ->exists();
    }

    public function registerDevice($user, $fingerprint, $deviceName)
    {
        return Device::updateOrCreate(
            ['user_id' => $user->id, 'fingerprint' => $fingerprint],
            ['device_name' => $deviceName, 'last_used_at' => now()]
        );
    }

    protected function getPlatform($userAgent)
    {
        if (str_contains($userAgent, 'Windows')) return 'Windows';
        if (str_contains($userAgent, 'Mac')) return 'MacOS';
        if (str_contains($userAgent, 'Linux')) return 'Linux';
        if (str_contains($userAgent, 'Android')) return 'Android';
        if (str_contains($userAgent, 'iPhone')) return 'iOS';
        return 'Unknown';
    }
}