<?php

return [
    'default' => 'aes-256-gcm',
    'key_rotation_days' => 90,
    'master_key' => env('ENCRYPTION_MASTER_KEY'),
    'algorithms' => [
        'aes-256-gcm' => ['key_size' => 32, 'iv_length' => 12],
        'aes-256-cbc' => ['key_size' => 32, 'iv_length' => 16],
        'chacha20-poly1305' => ['key_size' => 32, 'iv_length' => 12],
    ],
    'key_vault' => [
        'driver' => env('KEY_VAULT_DRIVER', 'database'),
        'azure' => ['tenant_id' => env('AZURE_TENANT_ID'), 'client_id' => env('AZURE_CLIENT_ID'), 'client_secret' => env('AZURE_CLIENT_SECRET'), 'vault_name' => env('AZURE_KEY_VAULT_NAME')],
        'aws' => ['region' => env('AWS_DEFAULT_REGION'), 'key' => env('AWS_ACCESS_KEY_ID'), 'secret' => env('AWS_SECRET_ACCESS_KEY'), 'kms_key_id' => env('AWS_KMS_KEY_ID')],
    ],
];