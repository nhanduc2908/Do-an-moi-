<?php

namespace App\Services\Module17_PhysicalSecurity;

use App\Models\Module17_PhysicalSecurity\SmartLock;

class SmartLockService
{
    public function lockDoor($lockId)
    {
        $lock = SmartLock::findOrFail($lockId);
        
        // Integrate with IoT lock API
        $lock->is_locked = true;
        $lock->save();
        
        return $lock;
    }

    public function unlockDoor($lockId, $credentials)
    {
        $lock = SmartLock::findOrFail($lockId);
        
        if ($this->validateCredentials($lock, $credentials)) {
            $lock->is_locked = false;
            $lock->save();
            
            $this->logAccess($lock, $credentials['user_id'], 'unlock');
            
            return ['success' => true];
        }
        
        $this->logAccess($lock, null, 'failed_attempt');
        
        return ['success' => false, 'reason' => 'Invalid credentials'];
    }

    protected function validateCredentials($lock, $credentials)
    {
        // Validate PIN, biometric, RFID, etc.
        return true;
    }

    protected function logAccess($lock, $userId, $action)
    {
        AccessLog::create([
            'door_id' => $lock->id,
            'user_id' => $userId,
            'action' => $action,
            'timestamp' => now()
        ]);
    }

    public function getLockStatus($lockId)
    {
        $lock = SmartLock::findOrFail($lockId);
        
        return [
            'lock_id' => $lock->lock_id,
            'door_name' => $lock->door_name,
            'is_locked' => $lock->is_locked,
            'battery_level' => $lock->battery_level,
            'is_online' => $lock->is_online,
            'last_activity' => $lock->last_activity_at
        ];
    }

    public function getAccessHistory($lockId, $days = 7)
    {
        $lock = SmartLock::findOrFail($lockId);
        
        return AccessLog::where('door_id', $lock->id)
            ->where('accessed_at', '>=', now()->subDays($days))
            ->orderBy('accessed_at', 'desc')
            ->get();
    }
}