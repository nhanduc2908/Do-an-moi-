<?php

namespace App\Exceptions;

use Exception;

class IpBlockedException extends Exception
{
    protected $message = 'Địa chỉ IP của bạn đã bị chặn';
    protected $code = 403;

    protected $ipAddress;
    protected $blockedUntil;
    protected $reason;
    protected $attempts;

    public function __construct($message = null, $ipAddress = null, $blockedUntil = null, $reason = null, $attempts = null)
    {
        $this->ipAddress = $ipAddress;
        $this->blockedUntil = $blockedUntil;
        $this->reason = $reason;
        $this->attempts = $attempts;
        
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $this->code);
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function getBlockedUntil()
    {
        return $this->blockedUntil;
    }

    public function getAttempts()
    {
        return $this->attempts;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'ip_blocked',
            'message' => $this->message,
            'ip_address' => $this->ipAddress,
            'blocked_until' => $this->blockedUntil,
            'reason' => $this->reason,
            'attempts' => $this->attempts,
            'code' => 'IP_BLOCKED'
        ], $this->code);
    }
}