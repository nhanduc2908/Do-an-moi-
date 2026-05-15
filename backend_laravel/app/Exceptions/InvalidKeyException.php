<?php

namespace App\Exceptions;

use Exception;

class InvalidKeyException extends Exception
{
    protected $message = 'Khóa bảo mật không hợp lệ';
    protected $code = 401;

    public function __construct($message = null, $code = null)
    {
        if ($message) {
            $this->message = $message;
        }
        if ($code) {
            $this->code = $code;
        }
        parent::__construct($this->message, $this->code);
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'invalid_key',
            'message' => $this->message,
            'code' => 'KEY_INVALID'
        ], $this->code);
    }
}