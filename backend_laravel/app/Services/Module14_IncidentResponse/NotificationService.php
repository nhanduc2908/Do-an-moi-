<?php

namespace App\Services\Module14_IncidentResponse;

use App\Models\Module14_IncidentResponse\Incident;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function notifyIncidentCreated($incident)
    {
        $recipients = $this->getRecipients($incident->severity);
        
        foreach ($recipients as $recipient) {
            Mail::send('emails.incident_created', ['incident' => $incident], function($message) use ($recipient) {
                $message->to($recipient['email'])
                        ->subject("[INCIDENT] {$incident->incident_code}: {$incident->title}");
            });
        }
        
        // Send SMS for high severity
        if (in_array($incident->severity, ['high', 'critical'])) {
            $this->sendSmsAlerts($incident);
        }
    }

    public function notifyStatusUpdate($incident, $oldStatus, $newStatus)
    {
        $recipients = $this->getRecipients($incident->severity);
        
        foreach ($recipients as $recipient) {
            Mail::send('emails.incident_update', ['incident' => $incident, 'old_status' => $oldStatus, 'new_status' => $newStatus], function($message) use ($recipient) {
                $message->to($recipient['email'])
                        ->subject("[UPDATE] {$incident->incident_code} status changed to {$newStatus}");
            });
        }
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

    protected function sendSmsAlerts($incident)
    {
        // Integrate with SMS provider (Twilio, etc.)
    }
}