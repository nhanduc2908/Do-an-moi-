<?php

namespace App\Services\Module13_LoggingMonitoring;

use App\Models\Module13_LoggingMonitoring\CorrelationRule;

class EventCorrelationService
{
    protected $correlationRules = [];

    public function __construct()
    {
        $this->loadCorrelationRules();
    }

    protected function loadCorrelationRules()
    {
        $this->correlationRules = CorrelationRule::where('is_active', true)->get();
    }

    public function correlate($events)
    {
        $correlatedEvents = [];
        
        foreach ($this->correlationRules as $rule) {
            $matches = $this->applyRule($rule, $events);
            
            if (!empty($matches)) {
                $correlatedEvents[] = [
                    'rule' => $rule->rule_name,
                    'matches' => $matches,
                    'severity' => $rule->severity,
                    'timestamp' => now()
                ];
            }
        }
        
        return $correlatedEvents;
    }

    protected function applyRule($rule, $events)
    {
        $conditions = $rule->conditions;
        $matches = [];
        
        // Implement complex event processing logic
        foreach ($events as $event) {
            if ($this->eventMatchesCondition($event, $conditions)) {
                $matches[] = $event;
            }
        }
        
        return $this->applyTimeWindow($matches, $conditions['time_window'] ?? 300);
    }

    protected function eventMatchesCondition($event, $conditions)
    {
        foreach ($conditions as $key => $value) {
            if ($key === 'time_window') continue;
            
            if (!isset($event[$key]) || $event[$key] !== $value) {
                return false;
            }
        }
        
        return true;
    }

    protected function applyTimeWindow($matches, $timeWindow)
    {
        if (count($matches) < 2) return [];
        
        $timestamps = array_column($matches, 'timestamp');
        $first = min($timestamps);
        $last = max($timestamps);
        
        if (strtotime($last) - strtotime($first) <= $timeWindow) {
            return $matches;
        }
        
        return [];
    }

    public function createCorrelationRule($data)
    {
        return CorrelationRule::create([
            'rule_name' => $data['name'],
            'description' => $data['description'],
            'conditions' => $data['conditions'],
            'severity' => $data['severity'],
            'action' => $data['action'],
            'is_active' => true,
            'priority' => $data['priority'] ?? 1,
            'created_by' => auth()->id()
        ]);
    }
}