<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;
    public $downloadUrl;

    public function __construct($report, $downloadUrl)
    {
        $this->report = $report;
        $this->downloadUrl = $downloadUrl;
    }

    public function build()
    {
        return $this->subject('Your Report is Ready - ' . $this->report->report_name)
                    ->markdown('emails.report-ready')
                    ->with([
                        'reportName' => $this->report->report_name,
                        'reportType' => $this->report->report_type,
                        'generatedAt' => $this->report->generated_at,
                        'downloadUrl' => $this->downloadUrl,
                        'expiresAt' => $this->report->expires_at
                    ]);
    }
}