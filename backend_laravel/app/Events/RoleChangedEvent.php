<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $oldRoleId;
    public $newRoleId;
    public $oldRoleName;
    public $newRoleName;
    public $changedBy;
    public $changedAt;
    public $reason;

    public function __construct($userId, $oldRoleId, $newRoleId, $oldRoleName, $newRoleName, $changedBy, $reason = null)
    {
        $this->userId = $userId;
        $this->oldRoleId = $oldRoleId;
        $this->newRoleId = $newRoleId;
        $this->oldRoleName = $oldRoleName;
        $this->newRoleName = $newRoleName;
        $this->changedBy = $changedBy;
        $this->changedAt = now();
        $this->reason = $reason;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->userId}"),
            new PrivateChannel("role.{$this->newRoleId}"),
            new PrivateChannel("audit.roles"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'old_role_id' => $this->oldRoleId,
            'new_role_id' => $this->newRoleId,
            'old_role_name' => $this->oldRoleName,
            'new_role_name' => $this->newRoleName,
            'changed_by' => $this->changedBy,
            'changed_at' => $this->changedAt->toIso8601String(),
            'reason' => $this->reason,
        ];
    }

    public function broadcastAs(): string
    {
        return 'role.changed';
    }
}