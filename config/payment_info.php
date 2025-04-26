<?php
// Load config from .env.php
$configPath = __DIR__ . '/../.env.php';

if (!file_exists($configPath)) {
    die('Missing .env.php configuration file.');
}

$config = include $configPath;


if (!defined('STRIPE_SECRET_KEY')) {
    define('STRIPE_SECRET_KEY', $config['STRIPE_SECRET_KEY']);
}
