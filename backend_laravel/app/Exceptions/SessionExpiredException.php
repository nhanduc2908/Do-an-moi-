<?php

namespace App\Exceptions;

use Exception;

class SessionExpiredException extends Exception
{
    protected $message = 'Phiên đăng nhập đã hết hạn';
    protected $code = 401;

    protected $sessionId;
    protected $expiredAt;
    protected $userId;

    public function __construct($message = null, $sessionId = null, $userId = null)
    {
        $this->sessionId = $sessionId;
        $this->userId = $userId;
        $this->expiredAt = now();
        
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $this->code);
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'session_expired',
            'message' => $this->message,
            'session_id' => $this->sessionId,
            'expired_at' => $this->expiredAt,
            'code' => 'SESSION_EXPIRED'
        ], $this->code);
    }
}