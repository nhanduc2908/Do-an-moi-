<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'log_type' => $this->log_type,
            'level' => $this->level,
            'message' => $this->message,
            'context' => $this->context,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', function() {
                return new UserResource($this->user);
            }),
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'url' => $this->url,
            'method' => $this->method,
            'duration_ms' => $this->duration_ms,
            'created_at' => $this->created_at,
        ];
    }
}