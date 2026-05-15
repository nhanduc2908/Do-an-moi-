<?php

return [
    'apps' => [['id' => env('PUSHER_APP_ID'), 'name' => env('APP_NAME'), 'key' => env('PUSHER_APP_KEY'), 'secret' => env('PUSHER_APP_SECRET'), 'path' => env('PUSHER_APP_PATH'), 'capacity' => null, 'enable_client_messages' => false, 'enable_statistics' => true]],
    'dashboard' => ['port' => env('LARAVEL_WEBSOCKETS_PORT', 6001), 'domain' => env('LARAVEL_WEBSOCKETS_DOMAIN', 'localhost'), 'path' => 'laravel-websockets'],
    'replication' => ['mode' => env('WEBSOCKETS_REPLICATION_MODE', 'local'), 'modes' => ['local' => ['host' => 'localhost', 'port' => 6001], 'redis' => ['host' => env('REDIS_HOST', '127.0.0.1'), 'port' => env('REDIS_PORT', 6379), 'password' => env('REDIS_PASSWORD')]]],
    'statistics' => ['model' => WebSocketLog::class, 'interval_in_seconds' => 60, 'delete_statistics_older_than_days' => 7, 'perform_dns_lookup' => false],
    'ssl' => ['local_cert' => env('LARAVEL_WEBSOCKETS_SSL_LOCAL_CERT'), 'local_pk' => env('LARAVEL_WEBSOCKETS_SSL_LOCAL_PK'), 'passphrase' => env('LARAVEL_WEBSOCKETS_SSL_PASSPHRASE'), 'verify_peer' => false, 'allow_self_signed' => true],
];