<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Config.php';
require __DIR__ . '/Http.php';
require __DIR__ . '/RateLimiter.php';
require __DIR__ . '/MailerService.php';
require __DIR__ . '/ContactHandler.php';

$config = Arch\Config::load(dirname(__DIR__));

if ($config->bool('debug', false)) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}
