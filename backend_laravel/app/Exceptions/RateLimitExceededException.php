<?php

namespace App\Exceptions;

use Exception;

class RateLimitExceededException extends Exception
{
    protected $message = 'Vượt quá giới hạn số lần yêu cầu';
    protected $code = 429;

    protected $retryAfter;
    protected $limit;
    protected $remaining;

    public function __construct($message = null, $retryAfter = 60, $limit = 100, $remaining = 0)
    {
        $this->retryAfter = $retryAfter;
        $this->limit = $limit;
        $this->remaining = $remaining;
        
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $this->code);
    }

    public function getRetryAfter()
    {
        return $this->retryAfter;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'rate_limit_exceeded',
            'message' => $this->message,
            'retry_after' => $this->retryAfter,
            'limit' => $this->limit,
            'remaining' => $this->remaining,
            'code' => 'RATE_LIMIT_EXCEEDED'
        ], $this->code)->withHeaders([
            'Retry-After' => $this->retryAfter,
            'X-RateLimit-Limit' => $this->limit,
            'X-RateLimit-Remaining' => $this->remaining,
        ]);
    }
}