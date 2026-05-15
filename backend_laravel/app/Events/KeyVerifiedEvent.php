<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KeyVerifiedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $keyId;
    public $userId;
    public $verificationStatus;
    public $verifiedAt;
    public $fingerprint;

    public function __construct($keyId, $userId, $verificationStatus, $fingerprint = null)
    {
        $this->keyId = $keyId;
        $this->userId = $userId;
        $this->verificationStatus = $verificationStatus;
        $this->verifiedAt = now();
        $this->fingerprint = $fingerprint;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->userId}"),
            new PrivateChannel("key.{$this->keyId}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'key_id' => $this->keyId,
            'user_id' => $this->userId,
            'status' => $this->verificationStatus,
            'verified_at' => $this->verifiedAt->toIso8601String(),
            'fingerprint' => $this->fingerprint,
        ];
    }

    public function broadcastAs(): string
    {
        return 'key.verified';
    }
}