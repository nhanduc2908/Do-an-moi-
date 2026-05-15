<?php

namespace App\Exceptions;

use Exception;

class KeyExpiredException extends Exception
{
    protected $message = 'Khóa bảo mật đã hết hạn';
    protected $code = 401;

    protected $keyId;
    protected $expiredAt;
    protected $userId;

    public function __construct($message = null, $keyId = null, $expiredAt = null, $userId = null)
    {
        $this->keyId = $keyId;
        $this->expiredAt = $expiredAt;
        $this->userId = $userId;
        
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $this->code);
    }

    public function getKeyId()
    {
        return $this->keyId;
    }

    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'key_expired',
            'message' => $this->message,
            'key_id' => $this->keyId,
            'expired_at' => $this->expiredAt,
            'code' => 'KEY_EXPIRED'
        ], $this->code);
    }
}