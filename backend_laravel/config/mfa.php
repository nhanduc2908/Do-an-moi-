<?php

return [
    'enabled' => true,
    'required_roles' => ['admin', 'super_admin', 'security_manager'],
    'optional_roles' => ['security_analyst', 'compliance_officer'],
    'methods' => ['authenticator_app' => ['priority' => 1, 'enabled' => true], 'sms' => ['priority' => 2, 'enabled' => true], 'email' => ['priority' => 3, 'enabled' => true], 'backup_codes' => ['priority' => 4, 'enabled' => true]],
    'code_length' => 6,
    'code_expiry_minutes' => 10,
    'backup_codes_count' => 10,
    'sms_provider' => env('SMS_PROVIDER', 'twilio'),
    'twilio' => ['account_sid' => env('TWILIO_ACCOUNT_SID'), 'auth_token' => env('TWILIO_AUTH_TOKEN'), 'from_number' => env('TWILIO_FROM_NUMBER')],
];