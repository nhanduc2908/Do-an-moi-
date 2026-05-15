<?php

namespace App\Services\Module06_ApiSecurity;

use Illuminate\Support\Facades\Http;

class ApiGatewayService
{
    protected $routes = [];
    protected $authService;
    protected $rateLimitService;

    public function __construct(ApiAuthService $authService, RateLimitService $rateLimitService)
    {
        $this->authService = $authService;
        $this->rateLimitService = $rateLimitService;
    }

    public function registerRoute($path, $target, $method = 'GET', $requireAuth = true)
    {
        $this->routes[$method][$path] = [
            'target' => $target,
            'require_auth' => $requireAuth
        ];
    }

    public function handleRequest($method, $path, $headers, $body = [])
    {
        // Validate API Key
        $apiKey = $headers['X-API-Key'] ?? null;
        if (!$apiKey) {
            return $this->errorResponse('API Key required', 401);
        }

        $key = $this->authService->validateApiKey($apiKey);
        if (!$key) {
            return $this->errorResponse('Invalid API Key', 401);
        }

        // Rate limiting
        $rateLimit = $this->rateLimitService->checkLimit($key->id);
        if (!$rateLimit['allowed']) {
            return $this->errorResponse('Rate limit exceeded', 429);
        }

        // Route to target
        $route = $this->routes[$method][$path] ?? null;
        if (!$route) {
            return $this->errorResponse('Route not found', 404);
        }

        // Forward request
        $response = Http::withHeaders($headers)
            ->{$method}($route['target'], $body);

        return $response->json();
    }

    protected function errorResponse($message, $code)
    {
        return [
            'success' => false,
            'error' => $message,
            'code' => $code
        ];
    }
}