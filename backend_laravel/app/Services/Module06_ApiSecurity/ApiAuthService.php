<?php

namespace App\Services\Module06_ApiSecurity;

use App\Models\Module06_ApiSecurity\ApiKey;
use Illuminate\Support\Str;

class ApiAuthService
{
    public function generateApiKey($userId, $name, $permissions = [])
    {
        $key = 'ak_' . Str::random(32);
        
        return ApiKey::create([
            'name' => $name,
            'key' => hash('sha256', $key),
            'user_id' => $userId,
            'permissions' => $permissions,
            'is_active' => true,
            'expires_at' => now()->addYear()
        ]);
    }

    public function validateApiKey($apiKey)
    {
        $hashedKey = hash('sha256', $apiKey);
        
        $key = ApiKey::where('key', $hashedKey)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if ($key) {
            $key->update(['last_used_at' => now()]);
            return $key;
        }

        return null;
    }

    public function revokeApiKey($keyId)
    {
        $key = ApiKey::findOrFail($keyId);
        $key->is_active = false;
        $key->save();
        
        return $key;
    }

    public function checkPermission($apiKey, $permission)
    {
        return in_array($permission, $apiKey->permissions);
    }
}