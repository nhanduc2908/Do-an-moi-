<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use App\Exceptions\RateLimitExceededException;

class RateLimitMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = $this->getMaxAttempts($request);
        $decayMinutes = $this->getDecayMinutes($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = $this->limiter->availableIn($key);
            
            throw new RateLimitExceededException(
                'Too many requests. Please try again later.',
                $retryAfter,
                $maxAttempts
            );
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        // Add rate limit headers
        return $this->addHeaders(
            $response,
            $maxAttempts,
            $this->limiter->retriesLeft($key, $maxAttempts),
            $this->limiter->availableIn($key)
        );
    }

    /**
     * Resolve request signature for rate limiting
     */
    private function resolveRequestSignature(Request $request): string
    {
        $user = $request->user();
        
        if ($user) {
            return sha1('user:' . $user->id . '|' . $request->ip());
        }
        
        return sha1('ip:' . $request->ip());
    }

    /**
     * Get max attempts based on request type
     */
    private function getMaxAttempts(Request $request): int
    {
        $user = $request->user();
        
        if ($user && $user->hasRole('admin')) {
            return 1000; // Admin higher limit
        }
        
        if ($user) {
            return 100; // Authenticated users
        }
        
        // Guest based on endpoint
        if ($request->is('api/login') || $request->is('api/register')) {
            return 10; // Login/register endpoints
        }
        
        return 60; // Default
    }

    /**
     * Get decay minutes
     */
    private function getDecayMinutes(Request $request): int
    {
        return 1; // 1 minute window
    }

    /**
     * Add rate limit headers to response
     */
    private function addHeaders($response, $limit, $remaining, $retryAfter): mixed
    {
        $response->headers->set('X-RateLimit-Limit', $limit);
        $response->headers->set('X-RateLimit-Remaining', $remaining);
        
        if ($retryAfter > 0) {
            $response->headers->set('Retry-After', $retryAfter);
        }
        
        return $response;
    }
}