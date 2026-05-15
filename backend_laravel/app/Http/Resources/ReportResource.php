<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'report_type' => $this->report_type,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'file_path' => $this->file_path,
            'file_size' => $this->file_size,
            'file_format' => $this->file_format,
            'filters' => $this->filters,
            'generated_by' => $this->generated_by,
            'generator' => $this->whenLoaded('generator', function() {
                return new UserResource($this->generator);
            }),
            'generated_at' => $this->generated_at,
            'expires_at' => $this->expires_at,
            'download_count' => $this->download_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}