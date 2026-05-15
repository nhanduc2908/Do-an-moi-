<?php

namespace App\Services\Module05_UrlSecurity;

use App\Models\Module05_UrlSecurity\PhishingUrl;

class PhishingDetectionService
{
    protected $suspiciousKeywords = [
        'login', 'verify', 'secure', 'account', 'update',
        'confirm', 'signin', 'banking', 'paypal', 'amazon'
    ];

    protected $legitimateDomains = [
        'google.com', 'facebook.com', 'amazon.com', 'microsoft.com'
    ];

    public function detectPhishing($url)
    {
        $score = 0;
        $reasons = [];

        $parsed = parse_url($url);
        $domain = $parsed['host'] ?? '';
        
        // Check suspicious keywords
        foreach ($this->suspiciousKeywords as $keyword) {
            if (stripos($url, $keyword) !== false) {
                $score += 10;
                $reasons[] = "Contains suspicious keyword: $keyword";
            }
        }

        // Check domain age
        if ($this->isNewDomain($domain)) {
            $score += 20;
            $reasons[] = "Domain is newly registered";
        }

        // Check URL length
        if (strlen($url) > 100) {
            $score += 5;
            $reasons[] = "Unusually long URL";
        }

        // Check for IP address instead of domain
        if (preg_match('/\d+\.\d+\.\d+\.\d+/', $domain)) {
            $score += 30;
            $reasons[] = "IP address used instead of domain name";
        }

        $isPhishing = $score >= 40;

        if ($isPhishing) {
            $this->reportPhishing($url, $score, $reasons);
        }

        return [
            'is_phishing' => $isPhishing,
            'score' => $score,
            'reasons' => $reasons,
            'risk_level' => $this->getRiskLevel($score)
        ];
    }

    protected function isNewDomain($domain)
    {
        // Check domain age via WHOIS
        return false;
    }

    protected function getRiskLevel($score)
    {
        if ($score >= 70) return 'Critical';
        if ($score >= 50) return 'High';
        if ($score >= 30) return 'Medium';
        return 'Low';
    }

    protected function reportPhishing($url, $score, $reasons)
    {
        PhishingUrl::updateOrCreate(
            ['url' => $url],
            [
                'domain' => parse_url($url, PHP_URL_HOST),
                'report_count' => \DB::raw('report_count + 1'),
                'last_seen_at' => now(),
                'status' => 'active'
            ]
        );
    }
}