<?php

namespace App\Services\Module04_PasswordSecurity;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class PasswordLeakService
{
    protected $haveIBeenPwnedApi = 'https://api.pwnedpasswords.com/range/';

    public function checkLeaked($password)
    {
        $hash = strtoupper(sha1($password));
        $prefix = substr($hash, 0, 5);
        $suffix = substr($hash, 5);

        $response = Http::get($this->haveIBeenPwnedApi . $prefix);
        
        if ($response->successful()) {
            $hashes = explode("\r\n", $response->body());
            foreach ($hashes as $line) {
                list($foundSuffix, $count) = explode(':', $line);
                if ($foundSuffix === $suffix) {
                    return [
                        'leaked' => true,
                        'count' => (int)$count
                    ];
                }
            }
        }

        return ['leaked' => false];
    }

    public function checkLocalLeak($password, $userId)
    {
        return PasswordLeak::where('user_id', $userId)
            ->where('password_hash', Hash::make($password))
            ->exists();
    }

    public function reportLeak($userId, $passwordHash, $source)
    {
        return PasswordLeak::create([
            'user_id' => $userId,
            'password_hash' => $passwordHash,
            'leak_source' => $source,
            'detected_at' => now(),
            'is_resolved' => false
        ]);
    }
}