<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AIPredictionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'prediction_type' => $this->prediction_type,
            'input_data' => $this->input_data,
            'prediction' => $this->prediction,
            'confidence_score' => $this->confidence_score,
            'confidence_interval_lower' => $this->confidence_interval_lower,
            'confidence_interval_upper' => $this->confidence_interval_upper,
            'model_version' => $this->model_version,
            'features_used' => $this->features_used,
            'processing_time_ms' => $this->processing_time_ms,
            'status' => $this->status,
            'error_message' => $this->error_message,
            'requested_by' => $this->requested_by,
            'requester' => $this->whenLoaded('requester', function() {
                return new UserResource($this->requester);
            }),
            'created_at' => $this->created_at,
        ];
    }
}