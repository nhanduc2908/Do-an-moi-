<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'role' => $this->whenLoaded('role', function() {
                return new RoleResource($this->role);
            }),
            'permissions' => $this->whenLoaded('permissions', function() {
                return PermissionResource::collection($this->permissions);
            }),
            'department' => $this->department,
            'status' => $this->status,
            'last_login_at' => $this->last_login_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}