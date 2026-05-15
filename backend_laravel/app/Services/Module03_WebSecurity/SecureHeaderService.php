<?php

namespace App\Services\Module03_WebSecurity;

use Illuminate\Http\Response;

class SecureHeaderService
{
    protected $defaultHeaders = [
        'X-Frame-Options' => 'DENY',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains'
    ];

    public function setSecurityHeaders(Response $response)
    {
        foreach ($this->defaultHeaders as $key => $value) {
            $response->header($key, $value);
        }
        
        return $response;
    }

    public function setCspHeader(Response $response, $policy = null)
    {
        $defaultPolicy = "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'";
        
        $response->header('Content-Security-Policy', $policy ?? $defaultPolicy);
        
        return $response;
    }

    public function setHpkpHeader(Response $response, $publicKeys, $maxAge = 2592000)
    {
        $header = "pin-sha256=\"" . implode('"; pin-sha256="', $publicKeys) . "\"; max-age=" . $maxAge;
        $response->header('Public-Key-Pins', $header);
        
        return $response;
    }
}