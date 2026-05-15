<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $allowedOrigins = config('cors.allowed_origins', ['*']);
        $origin = $request->header('Origin');

        if ($this->isOriginAllowed($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, X-API-Key, X-API-Secret, X-Signature');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Max-Age', '86400');
        }

        // Handle preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            $response->setStatusCode(200);
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, X-API-Key, X-API-Secret, X-Signature');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }

    /**
     * Check if origin is allowed
     */
    private function isOriginAllowed($origin, array $allowedOrigins): bool
    {
        if (in_array('*', $allowedOrigins)) {
            return true;
        }

        foreach ($allowedOrigins as $allowed) {
            if ($allowed === $origin) {
                return true;
            }

            // Check wildcard subdomains *.example.com
            if (str_starts_with($allowed, '*.')) {
                $domain = substr($allowed, 2);
                if (str_ends_with($origin, $domain)) {
                    return true;
                }
            }
        }

        return false;
    }
}