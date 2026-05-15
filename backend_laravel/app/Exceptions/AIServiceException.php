<?php

namespace App\Exceptions;

use Exception;

class AIServiceException extends Exception
{
    protected $message = 'Lỗi từ dịch vụ AI';
    protected $code = 503;

    protected $service;
    protected $endpoint;
    protected $response;

    public function __construct($message = null, $service = 'openai', $endpoint = null, $response = null)
    {
        $this->service = $service;
        $this->endpoint = $endpoint;
        $this->response = $response;
        
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $this->code);
    }

    public function getService()
    {
        return $this->service;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'ai_service_error',
            'message' => $this->message,
            'service' => $this->service,
            'endpoint' => $this->endpoint,
            'code' => 'AI_SERVICE_ERROR'
        ], $this->code);
    }
}