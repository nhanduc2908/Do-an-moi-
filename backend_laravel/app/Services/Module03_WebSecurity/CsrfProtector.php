<?php

namespace App\Services\Module03_WebSecurity;

use App\Models\Module03_WebSecurity\CsrfToken;
use Illuminate\Support\Str;

class CsrfProtector
{
    public function generateToken($userId = null)
    {
        $token = Str::random(64);
        
        CsrfToken::create([
            'token' => hash('sha256', $token),
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'expires_at' => now()->addHours(2)
        ]);
        
        return $token;
    }

    public function validateToken($token, $userId = null)
    {
        $hashedToken = hash('sha256', $token);
        
        $csrfToken = CsrfToken::where('token', $hashedToken)
            ->where('expires_at', '>', now())
            ->where('is_used', false)
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->first();
        
        if ($csrfToken) {
            $csrfToken->update(['is_used' => true]);
            return true;
        }
        
        return false;
    }

    public function getTokenMetaTag()
    {
        $token = $this->generateToken(auth()->id());
        return '<meta name="csrf-token" content="' . $token . '">';
    }
}