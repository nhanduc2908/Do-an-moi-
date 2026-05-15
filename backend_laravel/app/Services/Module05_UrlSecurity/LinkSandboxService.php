<?php

namespace App\Services\Module05_UrlSecurity;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LinkSandboxService
{
    public function analyzeLink($url)
    {
        $result = [
            'url' => $url,
            'final_url' => $url,
            'redirects' => [],
            'status_code' => null,
            'headers' => [],
            'content_preview' => null,
            'is_safe' => true,
            'warnings' => []
        ];

        try {
            $response = Http::timeout(30)
                ->withOptions(['allow_redirects' => ['track_redirects' => true]])
                ->get($url);

            $result['final_url'] = $response->effectiveUri();
            $result['status_code'] = $response->status();
            $result['headers'] = $response->headers();

            // Check for suspicious redirects
            if (count($response->redirectHistory()) > 3) {
                $result['warnings'][] = 'Multiple redirects detected';
                $result['is_safe'] = false;
            }

            // Check content for malicious patterns
            $content = $response->body();
            $result['content_preview'] = substr($content, 0, 500);

            if ($this->containsSuspiciousContent($content)) {
                $result['warnings'][] = 'Suspicious content detected';
                $result['is_safe'] = false;
            }

        } catch (\Exception $e) {
            Log::warning('Link sandbox analysis failed', ['url' => $url, 'error' => $e->getMessage()]);
            $result['is_safe'] = false;
            $result['warnings'][] = 'Could not analyze link: ' . $e->getMessage();
        }

        return $result;
    }

    protected function containsSuspiciousContent($content)
    {
        $suspiciousPatterns = [
            '/<script\b[^>]*>/i',
            '/on\w+\s*=/i',
            '/document\.write/i',
            '/eval\s*\(/i',
            '/base64_decode/i'
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }
}