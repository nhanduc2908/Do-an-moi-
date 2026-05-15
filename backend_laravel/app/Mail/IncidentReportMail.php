<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IncidentReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $incident;
    public $timeline;

    public function __construct($incident)
    {
        $this->incident = $incident;
        $this->timeline = $this->buildTimeline($incident);
    }

    public function build()
    {
        return $this->subject('Incident Report: ' . $this->incident->incident_code)
                    ->markdown('emails.incident-report')
                    ->with([
                        'incidentCode' => $this->incident->incident_code,
                        'title' => $this->incident->title,
                        'severity' => $this->incident->severity,
                        'status' => $this->incident->status,
                        'detectedAt' => $this->incident->detected_at,
                        'description' => $this->incident->description,
                        'timeline' => $this->timeline,
                        'assignedTo' => $this->incident->assignee?->name ?? 'Unassigned'
                    ]);
    }

    protected function buildTimeline($incident)
    {
        $timeline = [
            ['event' => 'Incident Created', 'time' => $incident->created_at],
        ];
        
        if ($incident->responded_at) {
            $timeline[] = ['event' => 'Incident Responded', 'time' => $incident->responded_at];
        }
        
        if ($incident->resolved_at) {
            $timeline[] = ['event' => 'Incident Resolved', 'time' => $incident->resolved_at];
        }
        
        return $timeline;
    }
}