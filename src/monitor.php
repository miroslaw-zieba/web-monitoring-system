<?php

require __DIR__ . '/vendor/autoload.php';

use Config\Config;
use Services\Database;
use Services\EmailNotifier;
use Services\PageMonitor;

// Inicjalizacja konfiguracji
$dbConfig = Config::getDbConfig();
$fromEmail = Config::getFromEmail();

// Tworzenie instancji serwisów
$db = new Database($dbConfig);
$admins = $db->getAdmins();
$emailNotifier = new EmailNotifier($admins, $fromEmail);

// Monitorowanie stron
$pageMonitor = new PageMonitor($db, $emailNotifier);
$pageMonitor->monitor();

echo "Monitoring zakończony.";
