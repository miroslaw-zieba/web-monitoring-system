<?php

namespace Config;

// Config class to store and retrieve configuration settings for the application
class Config
{
    // Static method to retrieve database configuration details
    public static function getDbConfig(): array
    {
        return [
            'host' => 'localhost', // Database host, typically 'localhost' for local development
            'dbname' => 'monitoring', // Name of the database being used
            'user' => 'root', // Database username, 'root' is common for local environments
            'pass' => '' // Database password, empty for local development (should be set for production)
        ];
    }

    // Static method to retrieve the "from" email address used in notifications
    public static function getFromEmail(): string
    {
        return 'monitor@yourdomain.com'; // Email address used as the sender for notification emails
    }
}
