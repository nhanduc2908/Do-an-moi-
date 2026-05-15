<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssessmentCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $assessment;
    public $score;

    public function __construct($assessment, $score)
    {
        $this->assessment = $assessment;
        $this->score = $score;
    }

    public function build()
    {
        return $this->subject('Security Assessment Complete - ' . $this->assessment->assessment_type)
                    ->markdown('emails.assessment-complete')
                    ->with([
                        'assessmentId' => $this->assessment->id,
                        'type' => $this->assessment->assessment_type,
                        'score' => $this->score,
                        'riskLevel' => $this->getRiskLevel($this->score),
                        'completedAt' => $this->assessment->completed_at
                    ]);
    }

    protected function getRiskLevel($score)
    {
        if ($score >= 80) return 'Low Risk';
        if ($score >= 60) return 'Medium Risk';
        if ($score >= 40) return 'High Risk';
        return 'Critical Risk';
    }
}