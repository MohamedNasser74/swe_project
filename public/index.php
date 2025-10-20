<?php
/**
 * Virtual Career Counseling Platform
 * Entry Point - Front Controller
 */

// Define root path
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Start session
session_start();

// Autoloader
spl_autoload_register(function($class) {
    // Core classes
    $file = APP_PATH . '/core/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // Models
    $file = APP_PATH . '/models/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // Controllers
    $file = APP_PATH . '/controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // Helpers
    $file = APP_PATH . '/helpers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
});

// Load configuration
require_once APP_PATH . '/config/config.php';

// Load constants
require_once APP_PATH . '/constants/Constants.php';

// If Composer autoload exists (PHPMailer installed), include it so Mailer can use it
$composerAutoload = ROOT_PATH . '/vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}

// Initialize application
$app = new App();
?>