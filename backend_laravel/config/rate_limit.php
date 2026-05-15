<?php

return [
    'enabled' => true,
    'defaults' => ['max_attempts' => 100, 'decay_minutes' => 1],
    'limits' => [
        'auth' => ['login' => ['max_attempts' => 5, 'decay_minutes' => 15], 'register' => ['max_attempts' => 3, 'decay_minutes' => 60], 'mfa' => ['max_attempts' => 3, 'decay_minutes' => 5]],
        'api' => ['default' => ['max_attempts' => 60, 'decay_minutes' => 1], 'critical' => ['max_attempts' => 10, 'decay_minutes' => 1]],
        'upload' => ['max_attempts' => 10, 'decay_minutes' => 60],
        'search' => ['max_attempts' => 30, 'decay_minutes' => 1],
    ],
];