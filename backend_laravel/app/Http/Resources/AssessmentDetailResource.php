<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assessment_id' => $this->assessment_id,
            'assessment' => $this->whenLoaded('assessment', function() {
                return new AssessmentResource($this->assessment);
            }),
            'criteria_id' => $this->criteria_id,
            'criteria' => $this->whenLoaded('criteria', function() {
                return new CriteriaResource($this->criteria);
            }),
            'response' => $this->response,
            'score' => $this->score,
            'evidence' => $this->evidence,
            'comments' => $this->comments,
            'status' => $this->status,
            'reviewed_by' => $this->reviewed_by,
            'reviewer' => $this->whenLoaded('reviewer', function() {
                return new UserResource($this->reviewer);
            }),
            'reviewed_at' => $this->reviewed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}