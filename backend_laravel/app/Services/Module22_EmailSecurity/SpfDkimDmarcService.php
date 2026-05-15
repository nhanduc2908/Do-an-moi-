<?php

namespace App\Services\Module22_EmailSecurity;

use App\Models\Module22_EmailSecurity\SpfRecord;
use App\Models\Module22_EmailSecurity\DkimRecord;
use App\Models\Module22_EmailSecurity\DmarcRecord;

class SpfDkimDmarcService
{
    public function checkSpf($domain)
    {
        $dns = dns_get_record($domain, DNS_TXT);
        $spfRecord = null;
        
        foreach ($dns as $record) {
            if (strpos($record['txt'], 'v=spf1') !== false) {
                $spfRecord = $record['txt'];
                break;
            }
        }
        
        $record = SpfRecord::updateOrCreate(
            ['domain' => $domain],
            [
                'record' => $spfRecord,
                'is_valid' => $spfRecord !== null,
                'last_checked_at' => now()
            ]
        );
        
        if ($spfRecord) {
            $record->allowed_ips = $this->parseSpfRecord($spfRecord);
            $record->save();
        }
        
        return $record;
    }

    protected function parseSpfRecord($spfRecord)
    {
        $ips = [];
        preg_match_all('/ip4:([0-9.]+)/', $spfRecord, $matches);
        $ips = array_merge($ips, $matches[1]);
        
        preg_match_all('/ip6:([a-f0-9:]+)/', $spfRecord, $matches);
        $ips = array_merge($ips, $matches[1]);
        
        return $ips;
    }

    public function checkDkim($domain, $selector = 'default')
    {
        $dns = dns_get_record("{$selector}._domainkey.{$domain}", DNS_TXT);
        $dkimRecord = $dns[0]['txt'] ?? null;
        
        $record = DkimRecord::updateOrCreate(
            ['domain' => $domain, 'selector' => $selector],
            [
                'public_key' => $this->extractPublicKey($dkimRecord),
                'is_valid' => $dkimRecord !== null,
                'last_checked_at' => now()
            ]
        );
        
        return $record;
    }

    protected function extractPublicKey($dkimRecord)
    {
        if (!$dkimRecord) return null;
        
        preg_match('/p=([A-Za-z0-9\/+=]+)/', $dkimRecord, $matches);
        return $matches[1] ?? null;
    }

    public function checkDmarc($domain)
    {
        $dns = dns_get_record("_dmarc.{$domain}", DNS_TXT);
        $dmarcRecord = $dns[0]['txt'] ?? null;
        
        $record = DmarcRecord::updateOrCreate(
            ['domain' => $domain],
            [
                'record' => $dmarcRecord,
                'is_valid' => $dmarcRecord !== null,
                'last_checked_at' => now()
            ]
        );
        
        if ($dmarcRecord) {
            $this->parseDmarcRecord($dmarcRecord, $record);
        }
        
        return $record;
    }

    protected function parseDmarcRecord($dmarcRecord, $record)
    {
        preg_match('/p=(\w+)/', $dmarcRecord, $policy);
        $record->policy = $policy[1] ?? 'none';
        
        preg_match('/sp=(\w+)/', $dmarcRecord, $subdomainPolicy);
        $record->subdomain_policy = $subdomainPolicy[1] ?? $record->policy;
        
        preg_match('/pct=(\d+)/', $dmarcRecord, $percentage);
        $record->percentage = $percentage[1] ?? 100;
        
        preg_match('/rua=mailto:([^\s]+)/', $dmarcRecord, $reportEmails);
        $record->report_emails = $reportEmails ? explode(',', $reportEmails[1]) : [];
        
        $record->save();
    }

    public function getEmailSecurityScore($domain)
    {
        $spf = $this->checkSpf($domain);
        $dkim = $this->checkDkim($domain);
        $dmarc = $this->checkDmarc($domain);
        
        $score = 0;
        $maxScore = 30;
        
        if ($spf->is_valid) $score += 10;
        if ($dkim->is_valid) $score += 10;
        if ($dmarc->is_valid && $dmarc->policy === 'reject') $score += 10;
        elseif ($dmarc->is_valid && $dmarc->policy === 'quarantine') $score += 5;
        
        return [
            'domain' => $domain,
            'score' => $score,
            'max_score' => $maxScore,
            'percentage' => ($score / $maxScore) * 100,
            'spf' => $spf,
            'dkim' => $dkim,
            'dmarc' => $dmarc,
            'recommendations' => $this->getRecommendations($spf, $dkim, $dmarc)
        ];
    }

    protected function getRecommendations($spf, $dkim, $dmarc)
    {
        $recommendations = [];
        
        if (!$spf->is_valid) {
            $recommendations[] = 'Implement SPF record to prevent email spoofing';
        }
        
        if (!$dkim->is_valid) {
            $recommendations[] = 'Implement DKIM signing for outgoing emails';
        }
        
        if (!$dmarc->is_valid) {
            $recommendations[] = 'Implement DMARC policy to monitor email authentication';
        } elseif ($dmarc->policy !== 'reject') {
            $recommendations[] = 'Set DMARC policy to "reject" for maximum protection';
        }
        
        return $recommendations;
    }
}