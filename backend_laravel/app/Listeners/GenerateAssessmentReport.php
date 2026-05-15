<?php

namespace App\Listeners;

use App\Events\AssessmentCompleted;
use App\Jobs\GenerateWeeklyReportJob;

class GenerateAssessmentReport
{
    public function handle(AssessmentCompleted $event)
    {
        $reportData = [
            'assessment_id' => $event->assessment->id,
            'score' => $event->assessment->score,
            'findings' => $event->findings,
            'recommendations' => $event->recommendations
        ];
        
        // Store report
        $reportPath = storage_path("reports/assessment_{$event->assessment->id}.json");
        file_put_contents($reportPath, json_encode($reportData));
        
        // Notify stakeholders
        $stakeholders = $event->assessment->assignedTeam ?? [];
        foreach ($stakeholders as $user) {
            Mail::to($user->email)->send(new AssessmentCompleteMail($event->assessment));
        }
    }
}