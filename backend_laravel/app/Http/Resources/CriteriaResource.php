<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CriteriaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'domain_id' => $this->domain_id,
            'domain' => $this->whenLoaded('domain', function() {
                return new DomainResource($this->domain);
            }),
            'weight' => $this->weight,
            'scoring_method' => $this->scoring_method,
            'max_score' => $this->max_score,
            'min_score' => $this->min_score,
            'passing_score' => $this->passing_score,
            'evidence_required' => $this->evidence_required,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}