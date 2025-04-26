<?php
// Load config from .env.php
$configPath = __DIR__ . '/../.env.php';
if (!file_exists($configPath)) {
    die('Missing .env.php configuration file.');
}

// Load environment variables into $config array
$config = include $configPath;


// === Define database constants if not already defined ===
if (!defined('DB_HOST')) {
  define('DB_HOST', $config['DB_HOST']);
}
if (!defined('DB_USER')) {
  define('DB_USER', $config['DB_USER']);
}
if (!defined('DB_PASS')) {
  define('DB_PASS', $config['DB_PASS']);
}
if (!defined('DB_NAME')) {
  define('DB_NAME', $config['DB_NAME']);
}

// === Establish MySQLi database connection ===
if (!isset($dbc)) {
  $dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
    or die('Could not connect to mySQL:' . mysqli_connect_error());
}

// Set connection character set to UTF-8
mysqli_set_charset($dbc, 'utf8');


// === String preparation helper to sanitize inputs ===
if (!function_exists('prepare_string')) {
  function prepare_string($dbc, $string)
  {
    $string = strip_tags($string);
    $string = mysqli_real_escape_string($dbc, trim($string));
    return $string;
  }
}
