<?php

namespace App\Services\Module01_IAM;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Module01_IAM\User;

class JwtService
{
    protected $secret;
    protected $algo;

    public function __construct()
    {
        $this->secret = config('jwt.secret');
        $this->algo = 'HS256';
    }

    public function generateToken($user)
    {
        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + config('jwt.ttl', 3600)
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algo));
            return User::find($decoded->sub);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function refreshToken($token)
    {
        $user = $this->validateToken($token);
        if ($user) {
            return $this->generateToken($user);
        }
        return null;
    }
}