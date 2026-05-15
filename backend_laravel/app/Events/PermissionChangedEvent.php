<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $permissionId;
    public $permissionName;
    public $roleId;
    public $action;
    public $changedBy;
    public $changedAt;

    public function __construct($permissionId, $permissionName, $roleId, $action, $changedBy)
    {
        $this->permissionId = $permissionId;
        $this->permissionName = $permissionName;
        $this->roleId = $roleId;
        $this->action = $action;
        $this->changedBy = $changedBy;
        $this->changedAt = now();
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("role.{$this->roleId}"),
            new PrivateChannel("permission.{$this->permissionId}"),
            new Channel("permissions"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'permission_id' => $this->permissionId,
            'permission_name' => $this->permissionName,
            'role_id' => $this->roleId,
            'action' => $this->action,
            'changed_by' => $this->changedBy,
            'changed_at' => $this->changedAt->toIso8601String(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'permission.changed';
    }
}