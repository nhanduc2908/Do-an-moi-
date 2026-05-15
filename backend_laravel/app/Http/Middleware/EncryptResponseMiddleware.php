<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\EncryptionService;

class EncryptResponseMiddleware
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Chỉ mã hóa response JSON cho API
        if ($request->expectsJson() && $request->header('X-Encrypt-Response') === 'true') {
            $content = $response->getContent();
            
            // Kiểm tra content là JSON hợp lệ
            if ($this->isJson($content)) {
                $encrypted = $this->encryptionService->encryptResponse($content);
                
                $response->setContent(json_encode([
                    'encrypted' => true,
                    'data' => $encrypted
                ]));
                
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('X-Encrypted', 'true');
            }
        }

        return $response;
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