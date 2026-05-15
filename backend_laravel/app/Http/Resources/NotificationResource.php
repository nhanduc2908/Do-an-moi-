<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data,
            'read_at' => $this->read_at,
            'is_read' => !is_null($this->read_at),
            'priority' => $this->priority,
            'sender_id' => $this->sender_id,
            'sender' => $this->whenLoaded('sender', function() {
                return new UserResource($this->sender);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}