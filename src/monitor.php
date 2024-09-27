<?php

require __DIR__ . '/vendor/autoload.php';

use Config\Config;
use Services\Database;
use Services\EmailNotifier;
use Services\PageMonitor;

// Initialize configuration
$dbConfig = Config::getDbConfig();
$fromEmail = Config::getFromEmail();

// Create instances of services
$db = new Database($dbConfig);
$admins = $db->getAdmins();
$emailNotifier = new EmailNotifier($admins, $fromEmail);

// Monitor websites
$pageMonitor = new PageMonitor($db, $emailNotifier);
$pageMonitor->monitor();

echo "Monitoring completed.";
