<?php

return [
    'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase-credentials.json')),
    'database' => ['url' => env('FIREBASE_DATABASE_URL')],
    'auth' => ['enabled' => true, 'tenant_id' => env('FIREBASE_TENANT_ID')],
    'storage' => ['default_bucket' => env('FIREBASE_STORAGE_BUCKET')],
    'messaging' => ['enabled' => true],
    'dynamic_links' => ['domain_uri_prefix' => env('FIREBASE_DYNAMIC_LINKS_DOMAIN')],
];