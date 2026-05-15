<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $email;
    public $method;
    public $sentAt;
    public $expiresAt;

    public function __construct($userId, $email, $method = 'email')
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->method = $method;
        $this->sentAt = now();
        $this->expiresAt = now()->addMinutes(10);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->userId}"),
            new Channel("twofactor"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'email' => $this->email,
            'method' => $this->method,
            'sent_at' => $this->sentAt->toIso8601String(),
            'expires_at' => $this->expiresAt->toIso8601String(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'twofactor.code_sent';
    }
}