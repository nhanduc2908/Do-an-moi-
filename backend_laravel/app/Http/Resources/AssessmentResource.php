<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assessment_type' => $this->assessment_type,
            'target_system_id' => $this->target_system_id,
            'target_system_name' => $this->whenLoaded('targetSystem', function() {
                return $this->targetSystem->name;
            }),
            'status' => $this->status,
            'progress' => $this->progress,
            'score' => $this->score,
            'risk_level' => $this->risk_level,
            'started_at' => $this->started_at,
            'completed_at' => $this->completed_at,
            'due_date' => $this->due_date,
            'assigned_to' => $this->assigned_to,
            'assigned_user' => $this->whenLoaded('assignedUser', function() {
                return new UserResource($this->assignedUser);
            }),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}