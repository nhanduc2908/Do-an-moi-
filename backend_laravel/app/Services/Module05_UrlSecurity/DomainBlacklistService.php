<?php

namespace App\Services\Module05_UrlSecurity;

use App\Models\Module05_UrlSecurity\DomainBlacklist;

class DomainBlacklistService
{
    public function addToBlacklist($domain, $reason, $expiresAt = null)
    {
        return DomainBlacklist::create([
            'domain' => $domain,
            'reason' => $reason,
            'added_by' => auth()->id(),
            'added_at' => now(),
            'expires_at' => $expiresAt
        ]);
    }

    public function removeFromBlacklist($domain)
    {
        return DomainBlacklist::where('domain', $domain)->delete();
    }

    public function isBlacklisted($domain)
    {
        return DomainBlacklist::where('domain', $domain)
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    public function getBlacklistedDomains()
    {
        return DomainBlacklist::orderBy('added_at', 'desc')->get();
    }
}