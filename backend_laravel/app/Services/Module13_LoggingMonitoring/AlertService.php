<?php

namespace App\Services\Module13_LoggingMonitoring;

use App\Models\Module13_LoggingMonitoring\Alert;
use App\Models\Module13_LoggingMonitoring\CorrelationRule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AlertService
{
    public function createAlert($data)
    {
        $alert = Alert::create([
            'alert_name' => $data['name'],
            'severity' => $data['severity'],
            'status' => 'new',
            'source' => $data['source'],
            'message' => $data['message'],
            'triggered_at' => now(),
            'details' => $data['details'] ?? [],
            'correlation_rule_id' => $data['rule_id'] ?? null
        ]);
        
        $this->processAlertActions($alert);
        
        return $alert;
    }

    public function acknowledgeAlert($alertId, $userId)
    {
        $alert = Alert::findOrFail($alertId);
        $alert->status = 'acknowledged';
        $alert->acknowledged_by = $userId;
        $alert->acknowledged_at = now();
        $alert->save();
        
        return $alert;
    }

    public function resolveAlert($alertId, $resolution)
    {
        $alert = Alert::findOrFail($alertId);
        $alert->status = 'resolved';
        $alert->resolved_at = now();
        $alert->details['resolution'] = $resolution;
        $alert->save();
        
        return $alert;
    }

    protected function processAlertActions($alert)
    {
        $severityActions = [
            'critical' => ['email', 'sms', 'webhook'],
            'high' => ['email', 'webhook'],
            'medium' => ['email'],
            'low' => ['log']
        ];
        
        $actions = $severityActions[$alert->severity] ?? ['log'];
        
        foreach ($actions as $action) {
            $method = "action_{$action}";
            if (method_exists($this, $method)) {
                $this->$method($alert);
            }
        }
    }

    protected function action_email($alert)
    {
        $recipients = config('alert.email_recipients', []);
        
        Mail::send('emails.alert', ['alert' => $alert], function ($message) use ($recipients) {
            $message->to($recipients)
                    ->subject("[ALERT] {$alert->severity}: {$alert->alert_name}");
        });
    }

    protected function action_sms($alert)
    {
        // Integrate with SMS provider
    }

    protected function action_webhook($alert)
    {
        $webhookUrl = config('alert.webhook_url');
        if ($webhookUrl) {
            Http::post($webhookUrl, $alert->toArray());
        }
    }

    protected function action_log($alert)
    {
        Log::channel('alert')->info('Alert triggered', $alert->toArray());
    }

    public function getActiveAlerts()
    {
        return Alert::whereIn('status', ['new', 'acknowledged'])
            ->orderBy('severity', 'desc')
            ->orderBy('triggered_at', 'desc')
            ->get();
    }
}