<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpWhitelist;
use App\Exceptions\IpBlockedException;

class IpWhitelistMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();

        // Kiểm tra whitelist
        $whitelist = IpWhitelist::where('is_active', true)
            ->where(function($query) use ($clientIp) {
                $query->where('ip_address', $clientIp)
                      ->orWhere('ip_range', 'like', $this->getIpRangePattern($clientIp));
            })
            ->exists();

        if (!$whitelist && config('security.ip_whitelist.enabled', false)) {
            // Ghi log truy cập bị chặn
            \Log::warning('IP blocked by whitelist', [
                'ip' => $clientIp,
                'path' => $request->path(),
                'user_id' => $request->user()?->id
            ]);

            throw new IpBlockedException(
                'Your IP address is not whitelisted',
                $clientIp,
                null,
                'IP not in whitelist'
            );
        }

        return $next($request);
    }

    /**
     * Get IP range pattern for LIKE query
     */
    private function getIpRangePattern($ip): string
    {
        $parts = explode('.', $ip);
        if (count($parts) === 4) {
            return $parts[0] . '.' . $parts[1] . '.%';
        }
        return '%';
    }
}