<?php

namespace App\Listeners;

use App\Events\IncidentCreated;
use App\Jobs\SendSecurityAlertJob;

class SendIncidentAlert
{
    public function handle(IncidentCreated $event)
    {
        $recipients = $this->getRecipients($event->incident->severity);
        
        $alertData = [
            'type' => 'incident',
            'severity' => $event->incident->severity,
            'title' => $event->incident->title,
            'description' => $event->incident->description,
            'incident_id' => $event->incident->incident_code,
            'timestamp' => now()
        ];
        
        dispatch(new SendSecurityAlertJob($recipients, $alertData));
    }

    protected function getRecipients($severity)
    {
        $config = config('incident.notifications', []);
        
        switch ($severity) {
            case 'critical':
                return $config['critical'] ?? [];
            case 'high':
                return $config['high'] ?? [];
            default:
                return $config['normal'] ?? [];
        }
    }
}