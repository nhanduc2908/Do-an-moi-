<?php

namespace App\Services\Module14_IncidentResponse;

use App\Models\Module14_IncidentResponse\RootCause;

class RootCauseAnalysisService
{
    public function performAnalysis($incidentId, $analysisData)
    {
        $rootCause = RootCause::create([
            'incident_id' => $incidentId,
            'cause_category' => $analysisData['category'],
            'description' => $analysisData['description'],
            'contributing_factors' => $analysisData['factors'],
            'underlying_issues' => $analysisData['issues'],
            'recommendations' => $analysisData['recommendations'],
            'analyzed_by' => auth()->id(),
            'analyzed_at' => now()
        ]);
        
        $this->generateReport($rootCause);
        
        return $rootCause;
    }

    protected function generateReport($rootCause)
    {
        $report = [
            'incident_id' => $rootCause->incident_id,
            'root_cause' => $rootCause->description,
            'contributing_factors' => $rootCause->contributing_factors,
            'underlying_issues' => $rootCause->underlying_issues,
            'recommendations' => $rootCause->recommendations,
            'analyzed_by' => $rootCause->analyst->name ?? 'Unknown',
            'analyzed_at' => $rootCause->analyzed_at
        ];
        
        // Save report to file
        $path = storage_path("reports/root_cause_{$rootCause->incident_id}.json");
        file_put_contents($path, json_encode($report, JSON_PRETTY_PRINT));
        
        return $path;
    }

    public function get5Whys($incidentId)
    {
        $incident = Incident::findOrFail($incidentId);
        $whys = [];
        
        $problem = $incident->description;
        $whys[] = [
            'level' => 1,
            'question' => "Why did {$problem} happen?",
            'answer' => $this->answerWhy($problem, 1)
        ];
        
        for ($i = 2; $i <= 5; $i++) {
            $whys[] = [
                'level' => $i,
                'question' => "Why did {$whys[$i-2]['answer']} happen?",
                'answer' => $this->answerWhy($whys[$i-2]['answer'], $i)
            ];
        }
        
        return $whys;
    }

    protected function answerWhy($question, $level)
    {
        // Implement logic to determine root cause at each level
        return "Root cause analysis level {$level} answer";
    }
}