<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\KeyVerificationService;
use App\Exceptions\InvalidKeyException;
use App\Exceptions\KeyExpiredException;

class KeyVerificationMiddleware
{
    protected $keyService;

    public function __construct(KeyVerificationService $keyService)
    {
        $this->keyService = $keyService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        $apiSecret = $request->header('X-API-Secret');
        $signature = $request->header('X-Signature');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key is required',
                'code' => 'MISSING_API_KEY'
            ], 401);
        }

        try {
            $verification = $this->keyService->verify([
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'signature' => $signature,
                'method' => $request->method(),
                'path' => $request->path(),
                'timestamp' => $request->header('X-Timestamp'),
            ]);

            if (!$verification['valid']) {
                throw new InvalidKeyException($verification['reason']);
            }

            // Gắn thông tin key vào request
            $request->attributes->set('api_key_info', $verification['key_info']);
            
            return $next($request);
            
        } catch (InvalidKeyException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 'INVALID_KEY'
            ], 401);
        } catch (KeyExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 'KEY_EXPIRED'
            ], 401);
        }
    }
}