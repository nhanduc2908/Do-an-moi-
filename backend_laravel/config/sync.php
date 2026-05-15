<?php

return [
    'flutter' => [
        'api_url' => env('FLUTTER_API_URL', 'https://flutter-api.security.com'),
        'api_key' => env('FLUTTER_API_KEY'),
        'timeout' => 30,
        'retry_attempts' => 3,
    ],
    'firebase' => [
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'database_url' => env('FIREBASE_DATABASE_URL'),
        'api_key' => env('FIREBASE_API_KEY'),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
    ],
    'websocket' => ['driver' => 'pusher', 'port' => env('WEBSOCKET_PORT', 6001), 'host' => env('WEBSOCKET_HOST', '127.0.0.1')],
    'sync_interval' => ['realtime' => 0, 'high' => 60, 'medium' => 300, 'low' => 3600],
];