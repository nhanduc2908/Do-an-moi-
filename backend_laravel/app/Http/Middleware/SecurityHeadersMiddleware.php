<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeadersMiddleware
{
    /**
     * Security headers configuration
     */
    protected $headers = [
        'X-Frame-Options' => 'DENY',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Set security headers
        foreach ($this->headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        // Content Security Policy (CSP)
        if (config('security.csp.enabled', true)) {
            $csp = $this->buildCspHeader($request);
            $response->headers->set('Content-Security-Policy', $csp);
        }

        // HSTS (only for HTTPS)
        if ($request->secure() && config('security.hsts.enabled', true)) {
            $maxAge = config('security.hsts.max_age', 31536000);
            $response->headers->set('Strict-Transport-Security', "max-age={$maxAge}; includeSubDomains; preload");
        }

        return $response;
    }

    /**
     * Build CSP header
     */
    private function buildCspHeader(Request $request): string
    {
        $directives = [
            'default-src' => ["'self'"],
            'script-src' => ["'self'", "'unsafe-inline'", "'unsafe-eval'"],
            'style-src' => ["'self'", "'unsafe-inline'"],
            'img-src' => ["'self'", 'data:', 'https:'],
            'font-src' => ["'self'"],
            'connect-src' => ["'self'"],
            'frame-ancestors' => ["'none'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
        ];

        // Allow API endpoints
        if ($request->is('api/*')) {
            $directives['default-src'][] = "'unsafe-inline'";
        }

        $csp = [];
        foreach ($directives as $directive => $sources) {
            $csp[] = $directive . ' ' . implode(' ', $sources);
        }

        return implode('; ', $csp);
    }
}