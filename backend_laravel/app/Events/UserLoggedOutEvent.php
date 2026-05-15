<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedOutEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $email;
    public $logoutAt;
    public $sessionId;
    public $reason;

    public function __construct($userId, $email, $sessionId, $reason = 'user_initiated')
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->logoutAt = now();
        $this->sessionId = $sessionId;
        $this->reason = $reason;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->userId}"),
            new Channel("user.logout"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'email' => $this->email,
            'logout_at' => $this->logoutAt->toIso8601String(),
            'session_id' => $this->sessionId,
            'reason' => $this->reason,
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.logged_out';
    }
}