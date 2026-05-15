<?php

namespace App\Services\Module03_WebSecurity;

class SqlInjectionDetector
{
    protected $patterns = [
        '/(\b(select|insert|update|delete|drop|union|create|alter|truncate)\b.*\b(from|into|set|where)\b)/i',
        '/(--|#|\/\*|\*\/|;)/',
        '/(\b(or|and)\b\s+\w+\s*=\s*\w+)/i',
        '/\b(union\s+select|select\s+.*\s+from)\b/i',
        '/\b(waitfor\s+delay|sleep\(|benchmark\()/i'
    ];

    public function detect($input)
    {
        $input = strtolower($input);
        
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return [
                    'detected' => true,
                    'pattern' => $pattern,
                    'matched' => $this->getMatchedPattern($pattern, $input)
                ];
            }
        }
        
        return ['detected' => false];
    }

    public function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        
        return addslashes(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
    }

    protected function getMatchedPattern($pattern, $input)
    {
        preg_match($pattern, $input, $matches);
        return $matches[0] ?? null;
    }
}