<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Các middleware global chạy cho mọi request
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        
        // Security middleware
        \App\Http\Middleware\BlockMaliciousRequests::class,
        \App\Http\Middleware\SecurityHeaders::class,
    ];

    /**
     * Các middleware groups cho ứng dụng
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            
            // Custom web middleware
            \App\Http\Middleware\CheckUserStatus::class,
            \App\Http\Middleware\LogUserActivity::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            
            // Custom API middleware
            \App\Http\Middleware\ApiResponseFormatter::class,
            \App\Http\Middleware\ValidateApiKey::class,
            \App\Http\Middleware\Cors::class,
            \App\Http\Middleware\LogApiRequest::class,
        ],
        
        'security' => [
            \App\Http\Middleware\EncryptionMiddleware::class,
            \App\Http\Middleware\RateLimiter::class,
            \App\Http\Middleware\IpWhitelist::class,
            \App\Http\Middleware\MfaVerification::class,
            \App\Http\Middleware\SessionValidator::class,
        ],
    ];

    /**
     * Các middleware aliases
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // Laravel default
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        
        // Custom security middleware
        'role' => \App\Http\Middleware\CheckRole::class,
        'permission' => \App\Http\Middleware\CheckPermission::class,
        'mfa' => \App\Http\Middleware\RequireMfa::class,
        'key.verify' => \App\Http\Middleware\VerifyKey::class,
        'rate.limit' => \App\Http\Middleware\RateLimit::class,
        'ip.block' => \App\Http\Middleware\BlockIp::class,
        'session.check' => \App\Http\Middleware\CheckSession::class,
        'encrypt' => \App\Http\Middleware\EncryptRequest::class,
        'decrypt' => \App\Http\Middleware\DecryptRequest::class,
        'audit.log' => \App\Http\Middleware\AuditLog::class,
        'version.access' => \App\Http\Middleware\CheckVersionAccess::class,
        'criteria.lock' => \App\Http\Middleware\CheckCriteriaLock::class,
        'domain.access' => \App\Http\Middleware\CheckDomainAccess::class,
        'assessment.lock' => \App\Http\Middleware\CheckAssessmentLock::class,
        
        // Performance middleware
        'cache.response' => \App\Http\Middleware\CacheResponse::class,
        'compress' => \App\Http\Middleware\CompressResponse::class,
        
        // Logging middleware
        'log.request' => \App\Http\Middleware\LogRequest::class,
        'log.response' => \App\Http\Middleware\LogResponse::class,
    ];

    /**
     * Priority middleware sorting
     *
     * @var array<int, class-string|string>
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
        
        // Security priority
        \App\Http\Middleware\BlockMaliciousRequests::class,
        \App\Http\Middleware\SecurityHeaders::class,
        \App\Http\Middleware\EncryptionMiddleware::class,
        \App\Http\Middleware\RateLimiter::class,
        \App\Http\Middleware\IpWhitelist::class,
        \App\Http\Middleware\CheckRole::class,
        \App\Http\Middleware\RequireMfa::class,
    ];
}