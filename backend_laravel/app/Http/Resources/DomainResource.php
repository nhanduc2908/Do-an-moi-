<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DomainResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'level' => $this->level,
            'criteria_count' => $this->whenCounted('criteria'),
            'assessments_count' => $this->whenCounted('assessments'),
            'status' => $this->status,
            'order' => $this->order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'children' => $this->whenLoaded('children', function() {
                return DomainResource::collection($this->children);
            }),
        ];
    }
}