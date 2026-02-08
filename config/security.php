<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Headers Configuration
    |--------------------------------------------------------------------------
    |
    | Security headers to protect your application from common attacks.
    | These will be applied to all responses in production environment.
    |
    */

    'headers' => [
        // Prevent clickjacking attacks
        'X-Frame-Options' => 'DENY',
        
        // Prevent MIME type sniffing
        'X-Content-Type-Options' => 'nosniff',
        
        // Enable XSS protection
        'X-XSS-Protection' => '1; mode=block',
        
        // Strict Transport Security (HTTPS only)
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        
        // Referrer policy
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        
        // Content Security Policy (adjust as needed)
        'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self';",
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Rate limiting settings to prevent abuse and ensure fair usage.
    |
    */

    'rate_limiting' => [
        'login_attempts' => 5,      // Max login attempts per minute
        'api_requests' => 60,       // Max API requests per minute per IP
        'password_reset' => 3,      // Max password reset requests per hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security Configuration
    |--------------------------------------------------------------------------
    |
    | Session security settings for production environment.
    |
    */

    'session' => [
        'secure' => env('SESSION_SECURE_COOKIE', true),      // Only send over HTTPS
        'http_only' => true,                                 // Prevent JavaScript access
        'same_site' => 'strict',                             // CSRF protection
        'regenerate_on_login' => true,                       // Regenerate session on login
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Security
    |--------------------------------------------------------------------------
    |
    | Database security settings.
    |
    */

    'database' => [
        'strict_mode' => true,                              // Enable strict SQL mode
        'prepared_statements' => true,                      // Use prepared statements
        'query_logging' => env('DB_QUERY_LOGGING', false),  // Log queries in development only
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | File upload security restrictions.
    |
    */

    'file_uploads' => [
        'max_size' => 10 * 1024 * 1024,                    // 10MB max file size
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'disallowed_extensions' => ['php', 'exe', 'bat', 'sh', 'js', 'html'],
    ],
];
