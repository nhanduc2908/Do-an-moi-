<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $digestData;
    public $weekRange;

    public function __construct($digestData)
    {
        $this->digestData = $digestData;
        $this->weekRange = now()->startOfWeek()->format('M d') . ' - ' . now()->endOfWeek()->format('M d, Y');
    }

    public function build()
    {
        return $this->subject('Weekly Security Digest - ' . $this->weekRange)
                    ->markdown('emails.weekly-digest')
                    ->with([
                        'weekRange' => $this->weekRange,
                        'securityScore' => $this->digestData['security_score'] ?? null,
                        'totalIncidents' => $this->digestData['incidents_count'] ?? 0,
                        'vulnerabilitiesFound' => $this->digestData['vulnerabilities_count'] ?? 0,
                        'completedAssessments' => $this->digestData['assessments_count'] ?? 0,
                        'topFindings' => $this->digestData['top_findings'] ?? [],
                        'recommendations' => $this->digestData['recommendations'] ?? [],
                        'trends' => $this->digestData['trends'] ?? []
                    ]);
    }
}