<?php
/**
 * PRODUCTION CONFIGURATION FILE
 * =============================
 * 
 * INSTRUCTIONS:
 * 1. After uploading to InfinityFree, rename this file to 'config.php'
 * 2. Replace the database credentials with your InfinityFree MySQL details
 * 3. Replace the Gemini API key (or keep the same one)
 * 4. Delete the original config.php (or keep as backup)
 */

// ============================================
// DATABASE SETTINGS - UPDATE THESE!
// ============================================
// Get these from InfinityFree Control Panel -> MySQL Databases
define('DB_HOST', 'sql123.infinityfree.com');  // Replace with your MySQL host
define('DB_USER', 'if0_12345678');              // Replace with your MySQL username
define('DB_PASS', 'your_password_here');        // Replace with your MySQL password
define('DB_NAME', 'if0_12345678_career');       // Replace with your database name

// ============================================
// APPLICATION SETTINGS
// ============================================
define('APP_NAME', 'Virtual Career Counseling Platform');

// Dynamically detect the base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Filesystem paths
$docRoot    = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : null;
$projectDir = dirname(dirname(__DIR__));              // .../swe_project
$publicPath = realpath($projectDir . '/public');      // .../swe_project/public

$baseUrl = $protocol . '://' . $host;

if ($docRoot && $publicPath && strpos($publicPath, $docRoot) === 0) {
    $relative = trim(str_replace('\\', '/', substr($publicPath, strlen($docRoot))), '/');
    if ($relative !== '') {
        $baseUrl .= '/' . $relative;
    }
} else {
    $scriptDir = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
    $baseUrl  .= rtrim(str_replace('\\', '/', $scriptDir), '/');
}

define('APP_URL', $baseUrl);
define('APP_VERSION', '1.0.0');

// ============================================
// EMAIL CONFIGURATION (Optional - for later)
// ============================================
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');

// ============================================
// FILE UPLOAD SETTINGS
// ============================================
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'jpg', 'png']);

// ============================================
// SESSION SETTINGS
// ============================================
define('SESSION_LIFETIME', 3600); // 1 hour

// ============================================
// TIMEZONE
// ============================================
date_default_timezone_set('UTC');

// ============================================
// PRODUCTION ERROR HANDLING
// ============================================
// IMPORTANT: Errors are hidden in production for security
error_reporting(0);
ini_set('display_errors', 0);

// Optional: Log errors to file instead
ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/logs/error.log');
?>
