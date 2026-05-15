<?php

namespace App\Services\Module03_WebSecurity;

class RceDetector
{
    protected $dangerousFunctions = [
        'eval', 'exec', 'system', 'shell_exec', 'passthru',
        'popen', 'proc_open', 'pcntl_exec', 'assert'
    ];

    protected $dangerousPatterns = [
        '/\$\{.*?\}/',
        '/`.*?`/',
        '/(\||&|;|`|\$\(\().*\w+\(.*\)/'
    ];

    public function detect($input)
    {
        foreach ($this->dangerousFunctions as $function) {
            if (preg_match('/\b' . preg_quote($function) . '\s*\(/i', $input)) {
                return [
                    'detected' => true,
                    'type' => 'dangerous_function',
                    'matched' => $function
                ];
            }
        }
        
        foreach ($this->dangerousPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return [
                    'detected' => true,
                    'type' => 'dangerous_pattern',
                    'matched' => $pattern
                ];
            }
        }
        
        return ['detected' => false];
    }

    public function sanitize($input)
    {
        foreach ($this->dangerousFunctions as $function) {
            $input = preg_replace('/\b' . preg_quote($function) . '\s*\(/i', '', $input);
        }
        
        return escapeshellcmd($input);
    }
}