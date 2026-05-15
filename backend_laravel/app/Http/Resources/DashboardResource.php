<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'summary' => [
                'total_assessments' => $this->total_assessments ?? 0,
                'completed_assessments' => $this->completed_assessments ?? 0,
                'in_progress_assessments' => $this->in_progress_assessments ?? 0,
                'average_score' => $this->average_score ?? 0,
                'critical_vulnerabilities' => $this->critical_vulnerabilities ?? 0,
                'open_incidents' => $this->open_incidents ?? 0,
                'compliance_rate' => $this->compliance_rate ?? 0,
                'active_keys' => $this->active_keys ?? 0,
            ],
            'recent_assessments' => AssessmentResource::collection($this->whenLoaded('recentAssessments')),
            'recent_reports' => ReportResource::collection($this->whenLoaded('recentReports')),
            'trends' => $this->trends ?? [],
            'charts' => $this->charts ?? [],
        ];
    }
}