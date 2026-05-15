<?php

namespace App\Services\Module09_NetworkSecurity;

use App\Models\Module09_NetworkSecurity\FirewallRule;
use Illuminate\Support\Facades\Cache;

class FirewallService
{
    public function addRule($data)
    {
        return FirewallRule::create($data);
    }

    public function removeRule($ruleId)
    {
        $rule = FirewallRule::findOrFail($ruleId);
        $rule->delete();
        $this->clearCache();
        return true;
    }

    public function evaluateRule($sourceIp, $destinationIp, $protocol, $port)
    {
        $rules = $this->getRules();
        
        foreach ($rules as $rule) {
            if ($this->matchesRule($rule, $sourceIp, $destinationIp, $protocol, $port)) {
                return [
                    'action' => $rule->action,
                    'rule_id' => $rule->id,
                    'rule_name' => $rule->name
                ];
            }
        }
        
        return ['action' => 'allow', 'rule_id' => null];
    }

    public function blockIp($ip, $reason, $duration = null)
    {
        return $this->addRule([
            'name' => 'Block IP: ' . $ip,
            'source_ip' => $ip,
            'action' => 'block',
            'priority' => 100,
            'is_enabled' => true,
            'description' => $reason,
            'expires_at' => $duration ? now()->addSeconds($duration) : null
        ]);
    }

    protected function getRules()
    {
        return Cache::remember('firewall_rules', 300, function() {
            return FirewallRule::where('is_enabled', true)
                ->where(function($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->orderBy('priority', 'desc')
                ->get();
        });
    }

    protected function matchesRule($rule, $sourceIp, $destinationIp, $protocol, $port)
    {
        if ($rule->source_ip && !$this->ipMatches($sourceIp, $rule->source_ip)) return false;
        if ($rule->destination_ip && !$this->ipMatches($destinationIp, $rule->destination_ip)) return false;
        if ($rule->protocol && $rule->protocol !== $protocol) return false;
        if ($rule->port && $rule->port != $port) return false;
        
        return true;
    }

    protected function ipMatches($ip, $cidr)
    {
        if (strpos($cidr, '/') === false) {
            return $ip === $cidr;
        }
        
        list($subnet, $mask) = explode('/', $cidr);
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = ~((1 << (32 - $mask)) - 1);
        
        return ($ipLong & $maskLong) == ($subnetLong & $maskLong);
    }

    protected function clearCache()
    {
        Cache::forget('firewall_rules');
    }
}