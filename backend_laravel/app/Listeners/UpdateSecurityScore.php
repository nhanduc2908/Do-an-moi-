<?php

namespace App\Listeners;

use App\Events\AssessmentCompleted;
use App\Events\VulnerabilityDetected;
use App\Events\IncidentResolved;
use App\Services\Module27_ReportAnalytics\SecurityScoreService;

class UpdateSecurityScore
{
    protected $scoreService;

    public function __construct(SecurityScoreService $scoreService)
    {
        $this->scoreService = $scoreService;
    }

    public function handle($event)
    {
        $organizationId = $this->getOrganizationId($event);
        
        if ($organizationId) {
            dispatch(new \App\Jobs\UpdateSecurityScoreJob($organizationId));
        }
    }

    protected function getOrganizationId($event)
    {
        if ($event instanceof AssessmentCompleted) {
            return $event->assessment->organization_id;
        }
        
        if ($event instanceof VulnerabilityDetected) {
            return $event->vulnerability->organization_id;
        }
        
        if ($event instanceof IncidentResolved) {
            return $event->incident->organization_id;
        }
        
        return null;
    }
}