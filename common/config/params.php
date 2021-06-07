<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    // jwt
    
    'jwt' => [
        'app_id' => 'mouse',
        'issuer' => 'https://school.aischool.vn',
        'audience' => 'https://school.aischool.vn',
        'at_expiration' => 4 * 3600, // access token expiration,
        'rt_expiration' => 7 * 24 * 3600, // refresh token expiration,
        'tdt_expiration' => 15 * 24 * 3600, // trusted device token
        'jti_length' => 16,
        'jti_password' => 'temp_password',
        'temp_password_expiration' => 365 * 24 * 3600, // temp password expiration
    ],
    'max_file_size' => 5*1024*1024,
];
