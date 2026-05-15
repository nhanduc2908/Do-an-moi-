<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Exceptions\SessionExpiredException;

class SessionTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $lastActivity = $request->session()->get('last_activity');
        $timeout = config('session.lifetime', 120); // minutes

        if ($lastActivity && (now()->timestamp - $lastActivity) > ($timeout * 60)) {
            $request->session()->flush();
            
            throw new SessionExpiredException(
                'Your session has expired due to inactivity',
                $request->session()->getId(),
                $user->id
            );
        }

        $request->session()->put('last_activity', now()->timestamp);

        return $next($request);
    }
}