<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'size' => $this->size,
            'purpose' => $this->purpose,
            'status' => $this->status,
            'fingerprint' => $this->fingerprint,
            'public_key' => $this->when($this->type !== 'AES', $this->public_key),
            'expires_at' => $this->expires_at,
            'revoked_at' => $this->revoked_at,
            'revocation_reason' => $this->revocation_reason,
            'metadata' => $this->metadata,
            'tags' => $this->tags,
            'created_by' => $this->created_by,
            'creator' => $this->whenLoaded('creator', function() {
                return new UserResource($this->creator);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}