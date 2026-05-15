<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Module14_IncidentResponse\IncidentService;
use App\Mail\IncidentReportMail;
use Illuminate\Support\Facades\Mail;

class ProcessIncidentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $incidentData;

    public function __construct(array $incidentData)
    {
        $this->incidentData = $incidentData;
    }

    public function handle(IncidentService $incidentService)
    {
        $incident = $incidentService->createIncident($this->incidentData);
        
        // Notify security team
        $securityTeam = config('incident.notifications.critical', []);
        foreach ($securityTeam as $recipient) {
            Mail::to($recipient['email'])->send(new IncidentReportMail($incident));
        }
        
        // Auto-assign based on severity
        if ($incident->severity === 'critical') {
            $incidentService->assignIncident($incident->id, $this->getOnCallEngineer());
        }
        
        \Log::info('Incident processed', ['incident_id' => $incident->incident_code]);
    }

    protected function getOnCallEngineer()
    {
        // Get on-call engineer from schedule
        return 1; // Return user ID
    }
}