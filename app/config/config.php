<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'career_counseling_platform');

// Application settings
define('APP_NAME', 'Virtual Career Counseling Platform');

// Dynamically detect the base URL so renaming folders won't break links
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Filesystem paths
$docRoot    = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : null;
$projectDir = dirname(dirname(__DIR__));              // .../swe_project
$publicPath = realpath($projectDir . '/public');      // .../swe_project/public

$baseUrl = $protocol . '://' . $host;

if ($docRoot && $publicPath && strpos($publicPath, $docRoot) === 0) {
    // Project is inside the web root → build URL from the public folder path
    $relative = trim(str_replace('\\', '/', substr($publicPath, strlen($docRoot))), '/');
    if ($relative !== '') {
        $baseUrl .= '/' . $relative;
    }
} else {
    // Fallback: use the current script URL directory
    $scriptDir = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
    $baseUrl  .= rtrim(str_replace('\\', '/', $scriptDir), '/');
}

define('APP_URL', $baseUrl);
define('APP_VERSION', '1.0.0');

// Email configuration (for later implementation)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');

// File upload settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'jpg', 'png']);

// Session settings
define('SESSION_LIFETIME', 3600); // 1 hour

// Timezone
date_default_timezone_set('UTC');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>