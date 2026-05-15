<?php

namespace App\Services\Module05_UrlSecurity;

use Illuminate\Support\Facades\Http;
use App\Models\Module05_UrlSecurity\UrlScanResult;

class UrlReputationService
{
    protected $googleSafeBrowsingApi;
    protected $virustotalApi;

    public function __construct()
    {
        $this->googleSafeBrowsingApi = config('services.google.safe_browsing_key');
        $this->virustotalApi = config('services.virustotal.api_key');
    }

    public function checkReputation($url)
    {
        $cached = UrlScanResult::where('url', $url)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if ($cached) {
            return $cached;
        }

        $googleResult = $this->checkGoogleSafeBrowsing($url);
        $virustotalResult = $this->checkVirusTotal($url);

        $result = $this->calculateReputation($googleResult, $virustotalResult);

        return UrlScanResult::create([
            'url' => $url,
            'status' => $result['status'],
            'risk_level' => $result['risk_level'],
            'is_malicious' => $result['is_malicious'],
            'categories' => $result['categories'],
            'scan_duration' => $result['duration']
        ]);
    }

    protected function checkGoogleSafeBrowsing($url)
    {
        $response = Http::post('https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . $this->googleSafeBrowsingApi, [
            'client' => ['clientId' => 'security-app', 'clientVersion' => '1.0.0'],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE'],
                'platformTypes' => ['ANY_PLATFORM'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [['url' => $url]]
            ]
        ]);

        return $response->json();
    }

    protected function checkVirusTotal($url)
    {
        $response = Http::withHeaders([
            'x-apikey' => $this->virustotalApi
        ])->get('https://www.virustotal.com/api/v3/urls/' . urlencode($url));

        return $response->json();
    }

    protected function calculateReputation($googleResult, $virustotalResult)
    {
        // Calculation logic
        return [
            'status' => 'checked',
            'risk_level' => 'low',
            'is_malicious' => false,
            'categories' => [],
            'duration' => 0.5
        ];
    }
}