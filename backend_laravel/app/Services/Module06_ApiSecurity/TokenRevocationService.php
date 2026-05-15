<?php

namespace App\Services\Module06_ApiSecurity;

use App\Models\Module06_ApiSecurity\ApiKey;
use Illuminate\Support\Facades\Cache;

class TokenRevocationService
{
    public function revokeToken($tokenId, $reason = null)
    {
        $token = ApiKey::findOrFail($tokenId);
        $token->is_active = false;
        $token->save();
        
        // Add to revocation cache
        Cache::put("revoked_token_{$token->key}", true, now()->addDays(30));
        
        return $token;
    }

    public function isRevoked($tokenHash)
    {
        return Cache::has("revoked_token_{$tokenHash}");
    }

    public function revokeAllUserTokens($userId)
    {
        $tokens = ApiKey::where('user_id', $userId)
            ->where('is_active', true)
            ->get();

        foreach ($tokens as $token) {
            $this->revokeToken($token->id, 'User token revocation');
        }

        return count($tokens);
    }
}