<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        AuthenticationException::class,
        ValidationException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        InvalidKeyException::class,
        VersionAccessDeniedException::class,
        RolePermissionException::class,
        AssessmentLockedException::class,
        KeyExpiredException::class,
        MfaRequiredException::class,
        SessionExpiredException::class,
        IpBlockedException::class,
        RateLimitExceededException::class,
        EncryptionException::class,
        AIServiceException::class,
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($e->getCode() >= 500) {
                \Log::error('Exception occurred: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }

    public function render($request, Throwable $e)
    {
        // Custom exception responses for API
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->renderApiException($e);
        }

        return parent::render($request, $e);
    }

    protected function renderApiException(Throwable $e)
    {
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
        $message = $e->getMessage();
        $code = $e->getCode();

        // Handle specific exceptions
        if ($e instanceof InvalidKeyException) {
            return response()->json([
                'success' => false,
                'error' => 'invalid_key',
                'message' => $message,
                'code' => 'KEY_INVALID'
            ], 401);
        }

        if ($e instanceof KeyExpiredException) {
            return response()->json([
                'success' => false,
                'error' => 'key_expired',
                'message' => $message,
                'code' => 'KEY_EXPIRED'
            ], 401);
        }

        if ($e instanceof VersionAccessDeniedException) {
            return response()->json([
                'success' => false,
                'error' => 'access_denied',
                'message' => $message,
                'code' => 'VERSION_ACCESS_DENIED'
            ], 403);
        }

        if ($e instanceof RolePermissionException) {
            return response()->json([
                'success' => false,
                'error' => 'permission_denied',
                'message' => $message,
                'code' => 'ROLE_PERMISSION_ERROR'
            ], 403);
        }

        if ($e instanceof AssessmentLockedException) {
            return response()->json([
                'success' => false,
                'error' => 'assessment_locked',
                'message' => $message,
                'code' => 'ASSESSMENT_LOCKED'
            ], 423);
        }

        if ($e instanceof MfaRequiredException) {
            return response()->json([
                'success' => false,
                'error' => 'mfa_required',
                'message' => $message,
                'code' => 'MFA_REQUIRED'
            ], 401);
        }

        if ($e instanceof SessionExpiredException) {
            return response()->json([
                'success' => false,
                'error' => 'session_expired',
                'message' => $message,
                'code' => 'SESSION_EXPIRED'
            ], 401);
        }

        if ($e instanceof IpBlockedException) {
            return response()->json([
                'success' => false,
                'error' => 'ip_blocked',
                'message' => $message,
                'code' => 'IP_BLOCKED'
            ], 403);
        }

        if ($e instanceof RateLimitExceededException) {
            return response()->json([
                'success' => false,
                'error' => 'rate_limit_exceeded',
                'message' => $message,
                'code' => 'RATE_LIMIT_EXCEEDED'
            ], 429);
        }

        if ($e instanceof EncryptionException) {
            return response()->json([
                'success' => false,
                'error' => 'encryption_error',
                'message' => $message,
                'code' => 'ENCRYPTION_ERROR'
            ], 500);
        }

        if ($e instanceof AIServiceException) {
            return response()->json([
                'success' => false,
                'error' => 'ai_service_error',
                'message' => $message,
                'code' => 'AI_SERVICE_ERROR'
            ], 503);
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'error' => 'validation_error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
                'code' => 'VALIDATION_ERROR'
            ], 422);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'error' => 'unauthenticated',
                'message' => 'Chưa đăng nhập',
                'code' => 'UNAUTHENTICATED'
            ], 401);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'error' => 'not_found',
                'message' => 'Không tìm thấy tài nguyên',
                'code' => 'NOT_FOUND'
            ], 404);
        }

        // Default error response
        return response()->json([
            'success' => false,
            'error' => 'server_error',
            'message' => app()->environment('production') ? 'Có lỗi xảy ra' : $message,
            'code' => 'SERVER_ERROR'
        ], $statusCode);
    }
}