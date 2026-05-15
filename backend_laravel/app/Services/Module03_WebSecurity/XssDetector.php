<?php

namespace App\Services\Module03_WebSecurity;

class XssDetector
{
    protected $patterns = [
        '/<script\b[^>]*>(.*?)<\/script>/is',
        '/javascript:/i',
        '/on\w+\s*=/i',
        '/<iframe\b[^>]*>/i',
        '/<object\b[^>]*>/i',
        '/<embed\b[^>]*>/i',
        '/&lt;script.*&gt;/i',
        '/&#x3C;script.*&#x3E;/i'
    ];

    public function detect($input)
    {
        $input = urldecode($input);
        
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return [
                    'detected' => true,
                    'type' => $this->getXssType($pattern),
                    'matched' => $this->getMatchedPattern($pattern, $input)
                ];
            }
        }
        
        return ['detected' => false];
    }

    public function sanitize($input)
    {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function getSecureHeaders()
    {
        return [
            'X-XSS-Protection' => '1; mode=block',
            'Content-Security-Policy' => "default-src 'self'",
            'X-Content-Type-Options' => 'nosniff'
        ];
    }

    protected function getXssType($pattern)
    {
        if (strpos($pattern, 'script') !== false) return 'Reflected';
        if (strpos($pattern, 'on') !== false) return 'DOM-based';
        if (strpos($pattern, 'iframe') !== false) return 'Stored';
        return 'Unknown';
    }

    protected function getMatchedPattern($pattern, $input)
    {
        preg_match($pattern, $input, $matches);
        return $matches[0] ?? null;
    }
}