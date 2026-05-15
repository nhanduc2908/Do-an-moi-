<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VersionUnlockedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $versionId;
    public $unlockedBy;
    public $unlockedAt;
    public $reason;
    public $previousVersionId;

    public function __construct($versionId, $unlockedBy, $reason = null, $previousVersionId = null)
    {
        $this->versionId = $versionId;
        $this->unlockedBy = $unlockedBy;
        $this->unlockedAt = now();
        $this->reason = $reason;
        $this->previousVersionId = $previousVersionId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("version.{$this->versionId}"),
            new PrivateChannel("user.{$this->unlockedBy}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'version_id' => $this->versionId,
            'unlocked_by' => $this->unlockedBy,
            'unlocked_at' => $this->unlockedAt->toIso8601String(),
            'reason' => $this->reason,
            'previous_version_id' => $this->previousVersionId,
        ];
    }

    public function broadcastAs(): string
    {
        return 'version.unlocked';
    }
}