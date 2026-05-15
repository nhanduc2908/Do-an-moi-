<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Exceptions\MfaRequiredException;

class MfaMiddleware
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

        // Kiểm tra MFA có bắt buộc không
        if ($this->isMfaRequired($user) && !$this->isMfaVerified($request, $user)) {
            throw new MfaRequiredException(
                'MFA verification required',
                $user->id,
                $user->getAvailableMfaMethods()
            );
        }

        return $next($request);
    }

    /**
     * Check if MFA is required for this user
     */
    private function isMfaRequired(User $user): bool
    {
        // Admin bắt buộc MFA
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            return true;
        }

        // User có MFA enabled thì bắt buộc
        if ($user->mfa_enabled) {
            return true;
        }

        // Cấu hình system yêu cầu MFA
        if (config('security.mfa.require_for_all', false)) {
            return true;
        }

        return false;
    }

    /**
     * Check if MFA is verified in current session
     */
    private function isMfaVerified(Request $request, User $user): bool
    {
        // Kiểm tra session MFA verified
        if ($request->session()->get('mfa_verified_' . $user->id)) {
            // Kiểm tra thời gian hết hạn
            $expiry = $request->session()->get('mfa_verified_at_' . $user->id);
            if ($expiry && now()->timestamp < $expiry) {
                return true;
            }
        }

        return false;
    }
}