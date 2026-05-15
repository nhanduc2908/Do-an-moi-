<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecurityAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $alertData;
    public $urgency;

    public function __construct($alertData)
    {
        $this->alertData = $alertData;
        $this->urgency = $alertData['severity'] ?? 'medium';
    }

    public function build()
    {
        $subject = match($this->urgency) {
            'critical' => '🚨 CRITICAL SECURITY ALERT',
            'high' => '⚠️ High Severity Security Alert',
            default => 'Security Alert Notification'
        };
        
        return $this->subject($subject)
                    ->markdown('emails.security-alert')
                    ->with([
                        'alertType' => $this->alertData['type'],
                        'severity' => $this->alertData['severity'],
                        'message' => $this->alertData['message'] ?? '',
                        'timestamp' => $this->alertData['timestamp'] ?? now(),
                        'actionRequired' => $this->getActionRequired(),
                        'link' => $this->alertData['link'] ?? null
                    ]);
    }

    protected function getActionRequired()
    {
        return in_array($this->urgency, ['critical', 'high']);
    }
}