<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogMiddleware
{
    /**
     * Events that should be audited
     */
    protected $auditableEvents = [
        'POST' => ['create', 'store', 'add', 'register'],
        'PUT' => ['update', 'edit', 'modify'],
        'PATCH' => ['update', 'modify'],
        'DELETE' => ['delete', 'remove', 'destroy'],
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only audit for authenticated users and success responses
        $user = $request->user();
        
        if ($user && $this->shouldAudit($request, $response)) {
            $this->logAudit($request, $response, $user);
        }

        return $response;
    }

    /**
     * Determine if request should be audited
     */
    private function shouldAudit(Request $request, $response): bool
    {
        // Only audit API and important web routes
        if (!$request->is('api/*') && !$request->is('admin/*')) {
            return false;
        }

        // Only audit non-GET requests
        if ($request->method() === 'GET') {
            return false;
        }

        // Only audit success responses (2xx)
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            return false;
        }

        return true;
    }

    /**
     * Log audit entry
     */
    private function logAudit(Request $request, $response, $user): void
    {
        $event = $this->determineEvent($request);
        $description = $this->buildDescription($request, $response);

        AuditLog::create([
            'user_id' => $user->id,
            'event' => $event,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'path' => $request->path(),
            'request_data' => $this->sanitizeRequestData($request->all()),
            'response_data' => $this->getResponseData($response),
            'status_code' => $response->getStatusCode(),
            'created_at' => now(),
        ]);
    }

    /**
     * Determine event type from request
     */
    private function determineEvent(Request $request): string
    {
        $method = $request->method();
        $path = $request->path();

        if (isset($this->auditableEvents[$method])) {
            foreach ($this->auditableEvents[$method] as $action) {
                if (str_contains($path, $action)) {
                    return $action;
                }
            }
        }

        return match($method) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'access',
        };
    }

    /**
     * Build description for audit log
     */
    private function buildDescription(Request $request, $response): string
    {
        $user = $request->user();
        $method = $request->method();
        $path = $request->path();

        return "User {$user->name} ({$user->email}) performed {$method} on {$path}";
    }

    /**
     * Sanitize request data (remove sensitive fields)
     */
    private function sanitizeRequestData(array $data): array
    {
        $sensitiveFields = ['password', 'password_confirmation', 'current_password', 'token', 'secret', 'api_key'];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[REDACTED]';
            }
        }

        return $data;
    }

    /**
     * Get response data (limited size)
     */
    private function getResponseData($response): ?array
    {
        $content = $response->getContent();
        
        if ($this->isJson($content)) {
            $data = json_decode($content, true);
            // Limit response data size
            return array_slice($data, 0, 50);
        }

        return null;
    }

    /**
     * Check if string is valid JSON
     */
    private function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}