<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserActivity;

class LogUserActivityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $user = $request->user();

        if ($user && $this->shouldLog($request)) {
            UserActivity::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => $request->method(),
                'path' => $request->path(),
                'status_code' => $response->getStatusCode(),
                'response_time' => $this->getResponseTime(),
                'created_at' => now(),
            ]);
        }

        return $response;
    }

    /**
     * Determine if this request should be logged
     */
    private function shouldLog(Request $request): bool
    {
        // Skip logging for static assets
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg)$/i', $request->path())) {
            return false;
        }

        // Skip logging for health checks
        if ($request->is('health') || $request->is('_health')) {
            return false;
        }

        return true;
    }

    /**
     * Get response time
     */
    private function getResponseTime(): float
    {
        return microtime(true) - LARAVEL_START;
    }
}