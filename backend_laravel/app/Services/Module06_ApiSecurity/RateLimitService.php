<?php

namespace App\Services\Module06_ApiSecurity;

use Illuminate\Support\Facades\Cache;

class RateLimitService
{
    public function checkLimit($key, $maxAttempts = 60, $decayMinutes = 1)
    {
        $cacheKey = "rate_limit_{$key}";
        
        $currentAttempts = Cache::get($cacheKey, 0);
        
        if ($currentAttempts >= $maxAttempts) {
            return [
                'allowed' => false,
                'retry_after' => Cache::ttl($cacheKey)
            ];
        }
        
        Cache::increment($cacheKey);
        Cache::put($cacheKey, $currentAttempts + 1, $decayMinutes * 60);
        
        return [
            'allowed' => true,
            'remaining' => $maxAttempts - ($currentAttempts + 1)
        ];
    }

    public function getRemainingAttempts($key, $maxAttempts = 60)
    {
        $currentAttempts = Cache::get("rate_limit_{$key}", 0);
        return max(0, $maxAttempts - $currentAttempts);
    }

    public function resetLimit($key)
    {
        Cache::forget("rate_limit_{$key}");
    }
}