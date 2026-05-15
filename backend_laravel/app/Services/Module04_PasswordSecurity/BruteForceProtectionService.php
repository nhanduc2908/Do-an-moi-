<?php

namespace App\Services\Module04_PasswordSecurity;

use App\Models\Module04_PasswordSecurity\BruteForceAttempt;
use Illuminate\Support\Facades\Cache;

class BruteForceProtectionService
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 15;

    public function incrementAttempts($email, $ip)
    {
        $attempt = BruteForceAttempt::firstOrCreate(
            [
                'user_id' => $this->getUserIdByEmail($email),
                'ip_address' => $ip
            ],
            ['attempts_count' => 0]
        );

        $attempt->increment('attempts_count');
        $attempt->last_attempt_at = now();
        
        if ($attempt->attempts_count >= $this->maxAttempts) {
            $attempt->is_blocked = true;
            Cache::put("brute_block_{$ip}", true, $this->decayMinutes * 60);
        }
        
        $attempt->save();

        return $this->getRemainingAttempts($email, $ip);
    }

    public function isBlocked($email, $ip)
    {
        if (Cache::has("brute_block_{$ip}")) {
            return true;
        }

        $attempt = BruteForceAttempt::where('ip_address', $ip)
            ->where('is_blocked', true)
            ->where('last_attempt_at', '>', now()->subMinutes($this->decayMinutes))
            ->first();

        return !is_null($attempt);
    }

    public function resetAttempts($email, $ip)
    {
        BruteForceAttempt::where('ip_address', $ip)->delete();
        Cache::forget("brute_block_{$ip}");
    }

    public function getRemainingAttempts($email, $ip)
    {
        $attempt = BruteForceAttempt::where('ip_address', $ip)->first();
        $attempts = $attempt ? $attempt->attempts_count : 0;
        
        return max(0, $this->maxAttempts - $attempts);
    }

    protected function getUserIdByEmail($email)
    {
        $user = \App\Models\Module01_IAM\User::where('email', $email)->first();
        return $user ? $user->id : null;
    }
}