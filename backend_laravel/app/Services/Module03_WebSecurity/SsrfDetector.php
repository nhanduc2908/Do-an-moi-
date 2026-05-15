<?php

namespace App\Services\Module03_WebSecurity;

use Illuminate\Support\Facades\Http;

class SsrfDetector
{
    protected $blockedIps = [
        '127.0.0.0/8',
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
        '169.254.0.0/16'
    ];

    protected $internalDomains = [
        'localhost',
        '*.local',
        '*.internal'
    ];

    public function detect($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        
        if ($this->isInternalIp($host)) {
            return ['detected' => true, 'reason' => 'Internal IP address'];
        }
        
        if ($this->isInternalDomain($host)) {
            return ['detected' => true, 'reason' => 'Internal domain'];
        }
        
        if ($this->checkDnsRebinding($url)) {
            return ['detected' => true, 'reason' => 'DNS rebinding detected'];
        }
        
        return ['detected' => false];
    }

    public function safeFetch($url)
    {
        $detection = $this->detect($url);
        
        if ($detection['detected']) {
            throw new \Exception('SSRF attempt detected: ' . $detection['reason']);
        }
        
        return Http::timeout(10)->get($url);
    }

    protected function isInternalIp($host)
    {
        $ip = gethostbyname($host);
        
        foreach ($this->blockedIps as $cidr) {
            if ($this->ipInCidr($ip, $cidr)) {
                return true;
            }
        }
        
        return false;
    }

    protected function isInternalDomain($host)
    {
        foreach ($this->internalDomains as $pattern) {
            if (fnmatch($pattern, $host)) {
                return true;
            }
        }
        return false;
    }

    protected function checkDnsRebinding($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        $firstIp = gethostbyname($host);
        sleep(2);
        $secondIp = gethostbyname($host);
        
        return $firstIp !== $secondIp;
    }

    protected function ipInCidr($ip, $cidr)
    {
        list($subnet, $mask) = explode('/', $cidr);
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = ~((1 << (32 - $mask)) - 1);
        
        return ($ipLong & $maskLong) == ($subnetLong & $maskLong);
    }
}