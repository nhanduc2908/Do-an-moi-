<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'level' => $this->level,
            'is_system_role' => $this->is_system_role,
            'permissions' => $this->whenLoaded('permissions', function() {
                return PermissionResource::collection($this->permissions);
            }),
            'users_count' => $this->whenCounted('users'),
            'created_at' => $this->created_at,
        ];
    }
}