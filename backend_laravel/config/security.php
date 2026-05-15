<?php

return [
    'password' => [
        'min_length' => 12,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_special' => true,
        'expiry_days' => 90,
        'history_count' => 5,
        'max_attempts' => 5,
        'lockout_minutes' => 15,
    ],
    'session' => [
        'lifetime' => 60,
        'idle_timeout' => 30,
        'absolute_timeout' => 480,
    ],
    'rate_limiting' => [
        'api' => ['max_attempts' => 100, 'decay_minutes' => 1],
        'login' => ['max_attempts' => 5, 'decay_minutes' => 15],
        'mfa' => ['max_attempts' => 3, 'decay_minutes' => 5],
    ],
    'headers' => [
        'x_frame_options' => 'DENY',
        'x_xss_protection' => '1; mode=block',
        'x_content_type_options' => 'nosniff',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => 'geolocation=(), microphone=(), camera=()',
    ],
    'csp' => [
        'enabled' => true,
        'default_src' => ["'self'"],
        'script_src' => ["'self'", "'unsafe-inline'"],
        'style_src' => ["'self'", "'unsafe-inline'"],
        'img_src' => ["'self'", 'data:'],
    ],
];