<?php

return [
    'enabled' => env('BACKUP_ENABLED', true),
    'schedule' => '0 2 * * *',
    'retention_days' => 30,
    'driver' => env('BACKUP_DRIVER', 'local'),
    'destinations' => ['local' => ['path' => storage_path('backups'), 'disk' => 'local'], 's3' => ['bucket' => env('AWS_BACKUP_BUCKET'), 'path' => 'backups', 'disk' => 's3']],
    'databases' => ['mysql' => ['enabled' => true, 'tables' => ['*'], 'exclude_tables' => ['sessions', 'cache', 'jobs']]],
    'files' => ['include' => ['storage/app', 'storage/logs'], 'exclude' => ['storage/framework', 'storage/backups', '*.tmp']],
    'encryption' => ['enabled' => true, 'algorithm' => 'aes-256-cbc', 'key' => env('BACKUP_ENCRYPTION_KEY')],
    'notifications' => ['enabled' => true, 'recipients' => ['admin@securityplatform.com'], 'on_success' => true, 'on_failure' => true],
];