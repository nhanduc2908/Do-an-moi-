<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'incident_id' => $this->incident_id,
            'title' => $this->title,
            'description' => $this->description,
            'severity' => $this->severity,
            'status' => $this->status,
            'category' => $this->category,
            'affected_systems' => $this->affected_systems,
            'reported_by' => $this->reported_by,
            'reporter' => $this->whenLoaded('reporter', function() {
                return new UserResource($this->reporter);
            }),
            'assigned_to' => $this->assigned_to,
            'assignee' => $this->whenLoaded('assignee', function() {
                return new UserResource($this->assignee);
            }),
            'resolved_at' => $this->resolved_at,
            'resolution_notes' => $this->resolution_notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}