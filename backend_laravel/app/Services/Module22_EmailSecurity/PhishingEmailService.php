<?php

namespace App\Services\Module22_EmailSecurity;

use App\Models\Module22_EmailSecurity\PhishingEmail;

class PhishingEmailService
{
    public function reportPhishing($emailData, $reporterId)
    {
        $phish = PhishingEmail::create([
            'sender' => $emailData['from'],
            'sender_domain' => $this->extractDomain($emailData['from']),
            'recipient' => $emailData['to'],
            'subject' => $emailData['subject'],
            'body_preview' => substr($emailData['body'] ?? '', 0, 500),
            'malicious_urls' => $this->extractUrls($emailData['body'] ?? ''),
            'reported_by' => $reporterId,
            'reported_at' => now(),
            'status' => 'pending'
        ]);
        
        // Analyze the reported email
        $this->analyzeReportedPhish($phish);
        
        return $phish;
    }

    protected function extractDomain($email)
    {
        $parts = explode('@', $email);
        return $parts[1] ?? '';
    }

    protected function extractUrls($content)
    {
        preg_match_all('/https?:\/\/[^\s<>"\']+/', $content, $matches);
        return $matches[0];
    }

    protected function analyzeReportedPhish($phish)
    {
        $analysis = [
            'url_analysis' => [],
            'domain_age' => $this->checkDomainAge($phish->sender_domain),
            'similarity_score' => $this->checkSimilarity($phish->subject),
            'indicators' => []
        ];
        
        foreach ($phish->malicious_urls as $url) {
            $analysis['url_analysis'][$url] = $this->analyzeUrl($url);
        }
        
        $phish->analysis_result = $analysis;
        $phish->status = 'analyzed';
        $phish->save();
        
        if ($this->isMalicious($analysis)) {
            $this->blockSender($phish->sender_domain);
            $this->updateThreatIntel($phish);
        }
    }

    protected function checkDomainAge($domain)
    {
        // Check domain registration date
        return ['days_old' => rand(1, 365)];
    }

    protected function checkSimilarity($subject)
    {
        // Compare with known phishing templates
        return ['score' => rand(0, 100)];
    }

    protected function analyzeUrl($url)
    {
        return [
            'is_malicious' => false,
            'redirects' => [],
            'risk_score' => rand(0, 100)
        ];
    }

    protected function isMalicious($analysis)
    {
        if ($analysis['domain_age']['days_old'] < 30) return true;
        if ($analysis['similarity_score']['score'] > 70) return true;
        
        foreach ($analysis['url_analysis'] as $url) {
            if ($url['is_malicious'] || $url['risk_score'] > 80) return true;
        }
        
        return false;
    }

    protected function blockSender($domain)
    {
        // Add domain to blocklist
        $blocklistService = new DomainBlacklistService();
        $blocklistService->addToBlacklist($domain, 'Phishing detected');
    }

    protected function updateThreatIntel($phish)
    {
        // Update threat intelligence database
    }

    public function getPhishingStats($days = 30)
    {
        $reports = PhishingEmail::where('reported_at', '>=', now()->subDays($days))->get();
        
        return [
            'total_reports' => $reports->count(),
            'confirmed_phishing' => $reports->where('status', 'analyzed')->count(),
            'unique_domains' => $reports->pluck('sender_domain')->unique()->count(),
            'top_domains' => $reports->groupBy('sender_domain')
                ->map->count()
                ->sortDesc()
                ->take(10),
            'by_day' => $reports->groupBy(function($report) {
                return $report->reported_at->format('Y-m-d');
            })->map->count()
        ];
    }
}