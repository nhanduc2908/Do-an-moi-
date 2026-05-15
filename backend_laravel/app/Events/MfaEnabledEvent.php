<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MfaEnabledEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $email;
    public $method;
    public $enabledAt;
    public $phoneNumber;

    public function __construct($userId, $email, $method = 'totp', $phoneNumber = null)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->method = $method;
        $this->enabledAt = now();
        $this->phoneNumber = $phoneNumber;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->userId}"),
            new PrivateChannel("security.mfa"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'email' => $this->email,
            'method' => $this->method,
            'enabled_at' => $this->enabledAt->toIso8601String(),
            'phone_number' => $this->phoneNumber,
        ];
    }

    public function broadcastAs(): string
    {
        return 'mfa.enabled';
    }
}