<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Module27_ReportAnalytics\ReportService;
use App\Mail\WeeklyDigestMail;
use Illuminate\Support\Facades\Mail;

class GenerateWeeklyReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $organizationId;
    protected $recipients;

    public function __construct($organizationId, array $recipients)
    {
        $this->organizationId = $organizationId;
        $this->recipients = $recipients;
    }

    public function handle(ReportService $reportService)
    {
        $report = $reportService->generateReport([
            'name' => 'Weekly Security Digest',
            'type' => 'security_summary',
            'format' => 'pdf',
            'filters' => [
                'period' => 'week',
                'organization_id' => $this->organizationId
            ]
        ]);
        
        foreach ($this->recipients as $recipient) {
            Mail::to($recipient)->send(new WeeklyDigestMail($report));
        }
    }
}