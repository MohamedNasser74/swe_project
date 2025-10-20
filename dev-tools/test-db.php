<?php
/**
 * Database Connection Test
 * Visit: http://localhost/project%20test1/public/test-db.php
 */

// Include configuration
require_once '../app/config/config.php';

echo "<h2>Virtual Career Counseling Platform - Database Test</h2>";

try {
    // Test database connection
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]
    );
    
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test if tables exist
    $tables = [
        'users',
        'student_profiles', 
        'counselor_profiles',
        'appointments',
        'forum_categories',
        'forum_topics',
        'resume_templates',
        'job_postings'
    ];
    
    echo "<h3>Table Structure Test:</h3>";
    echo "<ul>";
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM {$table}");
            $result = $stmt->fetch();
            echo "<li style='color: green;'>✅ Table '{$table}' exists with {$result->count} records</li>";
        } catch (Exception $e) {
            echo "<li style='color: red;'>❌ Table '{$table}' not found</li>";
        }
    }
    echo "</ul>";
    
    // Test admin user
    echo "<h3>Admin User Test:</h3>";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "<p style='color: green;'>✅ Admin user found:</p>";
        echo "<ul>";
        echo "<li><strong>Email:</strong> {$admin->email}</li>";
        echo "<li><strong>Username:</strong> {$admin->username}</li>";
        echo "<li><strong>Name:</strong> {$admin->first_name} {$admin->last_name}</li>";
        echo "<li><strong>Status:</strong> {$admin->status}</li>";
        echo "</ul>";
        echo "<p><strong>Login credentials:</strong> admin@careerplatform.com / admin123</p>";
    } else {
        echo "<p style='color: red;'>❌ Admin user not found</p>";
    }
    
    // Test application configuration
    echo "<h3>Application Configuration:</h3>";
    echo "<ul>";
    echo "<li><strong>App Name:</strong> " . APP_NAME . "</li>";
    echo "<li><strong>App URL:</strong> " . APP_URL . "</li>";
    echo "<li><strong>Database:</strong> " . DB_NAME . "</li>";
    echo "</ul>";
    
    echo "<h3>Quick Links:</h3>";
    echo "<ul>";
    echo "<li><a href='" . APP_URL . "'>Homepage</a></li>";
    echo "<li><a href='" . APP_URL . "/auth/login'>Login Page</a></li>";
    echo "<li><a href='" . APP_URL . "/auth/register'>Registration Page</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Make sure XAMPP MySQL service is running</li>";
    echo "<li>Check if database 'career_counseling_platform' exists</li>";
    echo "<li>Verify the database schema has been imported</li>";
    echo "<li>Check database credentials in app/config/config.php</li>";
    echo "</ul>";
}
?>

<style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    h2 { color: #007bff; }
    h3 { color: #333; margin-top: 30px; }
    ul { line-height: 1.6; }
    a { color: #007bff; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>