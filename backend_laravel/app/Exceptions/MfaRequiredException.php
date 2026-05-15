<?php

namespace App\Exceptions;

use Exception;

class MfaRequiredException extends Exception
{
    protected $message = 'Yêu cầu xác thực 2 yếu tố (MFA)';
    protected $code = 401;

    protected $userId;
    protected $sessionId;
    protected $availableMethods;

    public function __construct($message = null, $userId = null, $availableMethods = ['totp', 'email', 'sms'])
    {
        $this->userId = $userId;
        $this->availableMethods = $availableMethods;
        
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $this->code);
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getAvailableMethods()
    {
        return $this->availableMethods;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'mfa_required',
            'message' => $this->message,
            'user_id' => $this->userId,
            'available_methods' => $this->availableMethods,
            'code' => 'MFA_REQUIRED'
        ], $this->code);
    }
}