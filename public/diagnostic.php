<?php
/**
 * Quick Diagnostic Script
 * Check what's wrong with the application
 */

// Load configuration if available
$configFile = dirname(__DIR__) . '/app/config/config.php';
if (file_exists($configFile)) {
    require_once $configFile;
}

echo "<h2>System Diagnostic</h2>";
echo "<hr>";

// Check PHP version
echo "<h3>✓ PHP Version</h3>";
echo "PHP " . phpversion() . "<br>";

// Check if constants are loaded
echo "<h3>✓ Configuration</h3>";
echo "DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'NOT DEFINED') . "<br>";
echo "DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'NOT DEFINED') . "<br>";
echo "DB_USER: " . (defined('DB_USER') ? DB_USER : 'NOT DEFINED') . "<br>";
echo "APP_URL: " . (defined('APP_URL') ? APP_URL : 'NOT DEFINED') . "<br>";

// Check MySQL connection
echo "<h3>MySQL Connection</h3>";
try {
    $dsn = 'mysql:host=localhost;charset=utf8mb4';
    $pdo = new PDO($dsn, 'root', '');
    echo "✓ <span style='color:green'>MySQL server is running and accessible</span><br>";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE 'career_counseling_platform'");
    $dbExists = $stmt->fetch();
    
    if ($dbExists) {
        echo "✓ <span style='color:green'>Database 'career_counseling_platform' exists</span><br>";
        
        // Check tables
        $pdo->exec("USE career_counseling_platform");
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "✓ <span style='color:green'>Found " . count($tables) . " tables</span><br>";
        echo "Tables: " . implode(', ', $tables) . "<br>";
    } else {
        echo "✗ <span style='color:red'>Database 'career_counseling_platform' does NOT exist</span><br>";
        echo "<strong>ACTION REQUIRED:</strong> Import database/schema.sql into MySQL<br>";
    }
    
} catch (PDOException $e) {
    echo "✗ <span style='color:red'>MySQL connection failed</span><br>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "<strong>ACTION REQUIRED:</strong> Start MySQL in XAMPP Control Panel<br>";
}

// Check file permissions
echo "<h3>✓ File System</h3>";
echo "uploads/ writable: " . (is_writable(__DIR__ . '/uploads') ? 'YES' : 'NO') . "<br>";

// Check required directories
$dirs = ['css', 'js', 'images', 'uploads'];
foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    echo "$dir/ exists: " . (is_dir($path) ? 'YES' : 'NO') . "<br>";
}

echo "<hr>";
echo "<h3>Next Steps</h3>";
echo "<ol>";
echo "<li>Open XAMPP Control Panel</li>";
echo "<li>Start MySQL (if not running)</li>";
echo "<li>Import database/schema.sql via phpMyAdmin</li>";
echo "<li>Refresh this page to verify</li>";
echo "<li>Then visit: <a href='" . (defined('APP_URL') ? APP_URL : '/') . "'>" . (defined('APP_URL') ? APP_URL : 'Homepage') . "</a></li>";
echo "</ol>";
?>
