<?php

namespace App\Services\Module17_PhysicalSecurity;

use App\Models\Module17_PhysicalSecurity\BiometricRecord;

class BiometricService
{
    public function enrollBiometric($userId, $biometricData, $type)
    {
        $record = BiometricRecord::create([
            'user_id' => $userId,
            'biometric_type' => $type,
            'template_hash' => $this->hashBiometricTemplate($biometricData),
            'enrolled_at' => now(),
            'expires_at' => now()->addYears(5),
            'status' => 'active'
        ]);
        
        return $record;
    }

    public function verifyBiometric($userId, $biometricData, $type)
    {
        $record = BiometricRecord::where('user_id', $userId)
            ->where('biometric_type', $type)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();
        
        if (!$record) {
            return ['verified' => false, 'reason' => 'No biometric record found'];
        }
        
        $isMatch = $this->compareBiometricTemplate(
            $record->template_hash,
            $this->hashBiometricTemplate($biometricData)
        );
        
        if (!$isMatch) {
            $this->logFailedAttempt($userId, $type);
        }
        
        return [
            'verified' => $isMatch,
            'user_id' => $userId,
            'type' => $type
        ];
    }

    protected function hashBiometricTemplate($biometricData)
    {
        return hash('sha256', json_encode($biometricData));
    }

    protected function compareBiometricTemplate($hash1, $hash2)
    {
        return $hash1 === $hash2;
    }

    protected function logFailedAttempt($userId, $type)
    {
        \Log::warning('Failed biometric verification', [
            'user_id' => $userId,
            'biometric_type' => $type,
            'timestamp' => now(),
            'ip' => request()->ip()
        ]);
    }

    public function revokeBiometric($userId, $type)
    {
        BiometricRecord::where('user_id', $userId)
            ->where('biometric_type', $type)
            ->update(['status' => 'revoked']);
        
        return ['success' => true];
    }
}