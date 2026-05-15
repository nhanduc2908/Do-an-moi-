<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Kiểm tra role
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Kiểm tra level nếu role dạng level:10
        foreach ($roles as $role) {
            if (str_starts_with($role, 'level:')) {
                $requiredLevel = (int) substr($role, 6);
                if ($user->role && $user->role->level >= $requiredLevel) {
                    return $next($request);
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Forbidden - Insufficient permissions',
            'required_roles' => $roles
        ], 403);
    }
}