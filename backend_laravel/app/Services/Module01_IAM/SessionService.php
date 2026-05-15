<?php

namespace App\Services\Module01_IAM;

use App\Models\Module01_IAM\UserSession;
use Illuminate\Support\Facades\Session;

class SessionService
{
    public function createSession($user, $request)
    {
        return UserSession::create([
            'user_id' => $user->id,
            'session_id' => Session::getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'last_activity' => now(),
            'is_active' => true
        ]);
    }

    public function destroySession($sessionId)
    {
        $session = UserSession::where('session_id', $sessionId)->first();
        if ($session) {
            $session->update(['is_active' => false]);
        }
    }

    public function getActiveSessions($userId)
    {
        return UserSession::where('user_id', $userId)
            ->where('is_active', true)
            ->get();
    }

    public function destroyAllSessions($userId, $excludeCurrent = true)
    {
        $query = UserSession::where('user_id', $userId);
        if ($excludeCurrent) {
            $query->where('session_id', '!=', Session::getId());
        }
        $query->update(['is_active' => false]);
    }
}