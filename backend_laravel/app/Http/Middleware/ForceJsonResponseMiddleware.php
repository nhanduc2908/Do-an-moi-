<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceJsonResponseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Force Accept header to application/json for API routes
        if ($request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');
        }

        $response = $next($request);

        // Ensure response is JSON for API
        if ($request->is('api/*') && !$response->headers->get('Content-Type')) {
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}