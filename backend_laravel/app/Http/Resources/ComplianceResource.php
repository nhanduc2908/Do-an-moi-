<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplianceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'standard' => $this->standard,
            'control_id' => $this->control_id,
            'control_name' => $this->control_name,
            'description' => $this->description,
            'status' => $this->status,
            'implementation_status' => $this->implementation_status,
            'evidence' => $this->evidence,
            'owner' => $this->owner,
            'owner_user' => $this->whenLoaded('ownerUser', function() {
                return new UserResource($this->ownerUser);
            }),
            'last_reviewed_at' => $this->last_reviewed_at,
            'next_review_at' => $this->next_review_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}