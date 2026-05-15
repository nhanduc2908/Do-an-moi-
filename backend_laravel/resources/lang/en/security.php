<?php

return [
    'password_policy' => [
        'min_length' => 'Password must be at least :min characters.',
        'require_uppercase' => 'Password must contain at least one uppercase letter.',
        'require_lowercase' => 'Password must contain at least one lowercase letter.',
        'require_numbers' => 'Password must contain at least one number.',
        'require_special' => 'Password must contain at least one special character.',
        'expired' => 'Your password has expired. Please change your password.',
        'recently_used' => 'You have used this password recently. Please choose a new one.',
    ],
    'mfa' => [
        'setup_title' => 'Set Up Two-Factor Authentication',
        'scan_qr' => 'Scan the QR code with your authenticator app',
        'manual_code' => 'Or enter this code manually:',
        'verify_title' => 'Verify Authentication Code',
        'backup_codes' => 'Save your backup codes',
        'backup_codes_warning' => 'Store these backup codes in a safe place. Each code can only be used once.',
    ],
    'session' => [
        'expired' => 'Your session has expired due to inactivity.',
        'multiple_devices' => 'You are logged in on multiple devices.',
        'logout_all' => 'Logout from all devices',
    ],
    'encryption' => [
        'key_rotated' => 'Encryption key has been rotated successfully.',
        'key_revoked' => 'Encryption key has been revoked.',
        'decryption_failed' => 'Failed to decrypt data. Key may be invalid or corrupted.',
    ],
];