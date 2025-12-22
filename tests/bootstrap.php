<?php
/**
 * PHPUnit Bootstrap File
 * Sets up the testing environment
 */

// Define paths for testing
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Simulate session for tests
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once APP_PATH . '/config/config.php';

// Composer autoloader
require_once ROOT_PATH . '/vendor/autoload.php';

// Custom autoloader for app classes
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

/**
 * Base Test Case class with common utilities
 */
abstract class BaseTestCase extends PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Reset session for each test
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Helper to simulate a logged-in user
     */
    protected function actingAs($userId, $role = 'student', $name = 'Test User', $email = 'test@test.com')
    {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_role'] = $role;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
    }

    /**
     * Helper to clear session (logout)
     */
    protected function logout()
    {
        $_SESSION = [];
    }
}
