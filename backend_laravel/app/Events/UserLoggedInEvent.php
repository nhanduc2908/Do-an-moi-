<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedInEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $email;
    public $ipAddress;
    public $userAgent;
    public $loginAt;
    public $mfaVerified;

    public function __construct($userId, $email, $ipAddress, $userAgent, $mfaVerified = false)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->loginAt = now();
        $this->mfaVerified = $mfaVerified;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->userId}"),
            new Channel("user.login"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'email' => $this->email,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'login_at' => $this->loginAt->toIso8601String(),
            'mfa_verified' => $this->mfaVerified,
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.logged_in';
    }
}