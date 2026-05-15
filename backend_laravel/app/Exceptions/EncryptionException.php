<?php

namespace App\Exceptions;

use Exception;

class EncryptionException extends Exception
{
    protected $message = 'Lỗi mã hóa hoặc giải mã dữ liệu';
    protected $code = 500;

    protected $operation;
    protected $keyId;

    public function __construct($message = null, $operation = null, $keyId = null)
    {
        $this->operation = $operation;
        $this->keyId = $keyId;
        
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $this->code);
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function getKeyId()
    {
        return $this->keyId;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'encryption_error',
            'message' => $this->message,
            'operation' => $this->operation,
            'key_id' => $this->keyId,
            'code' => 'ENCRYPTION_ERROR'
        ], $this->code);
    }
}