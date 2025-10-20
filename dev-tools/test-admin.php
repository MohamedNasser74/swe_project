<?php
/**
 * Admin Login Test Page
 * Visit: http://localhost/project%20test1/public/test-admin.php
 */

// Include configuration
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

echo "<h2>Admin Login Test</h2>";

try {
    $db = new Database();
    
    // Check if admin user exists
    $db->query("SELECT * FROM users WHERE email = :email");
    $db->bind(':email', 'admin@careerplatform.com');
    $admin = $db->single();
    
    if ($admin) {
        echo "<div style='color: green; margin: 20px 0;'>";
        echo "<h3>✅ Admin User Found!</h3>";
        echo "<p><strong>Email:</strong> " . $admin->email . "</p>";
        echo "<p><strong>Username:</strong> " . $admin->username . "</p>";
        echo "<p><strong>Name:</strong> " . $admin->first_name . " " . $admin->last_name . "</p>";
        echo "<p><strong>Role:</strong> " . $admin->role . "</p>";
        echo "<p><strong>Status:</strong> " . $admin->status . "</p>";
        echo "<p><strong>Email Verified:</strong> " . ($admin->email_verified ? 'Yes' : 'No') . "</p>";
        echo "</div>";
        
        echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h3>🔐 Login Instructions:</h3>";
        echo "<ol>";
        echo "<li>Go to: <a href='" . APP_URL . "/auth/login' target='_blank'>" . APP_URL . "/auth/login</a></li>";
        echo "<li>Use these credentials:</li>";
        echo "<ul>";
        echo "<li><strong>Email:</strong> admin@careerplatform.com</li>";
        echo "<li><strong>Password:</strong> admin123</li>";
        echo "</ul>";
        echo "<li>Click 'Sign In'</li>";
        echo "<li>You will be redirected to: <a href='" . APP_URL . "/admin/dashboard' target='_blank'>" . APP_URL . "/admin/dashboard</a></li>";
        echo "</ol>";
        echo "</div>";
        
        // Test password verification
        $testPassword = 'admin123';
        if (password_verify($testPassword, $admin->password)) {
            echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h4>✅ Password Verification Test PASSED</h4>";
            echo "<p>The password 'admin123' correctly matches the stored hash.</p>";
            echo "</div>";
        } else {
            echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h4>❌ Password Verification Test FAILED</h4>";
            echo "<p>The password verification failed. Check the database.</p>";
            echo "</div>";
        }
        
    } else {
        echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h3>❌ Admin User Not Found!</h3>";
        echo "<p>The admin user does not exist in the database.</p>";
        echo "<p><strong>Solution:</strong> Make sure you have imported the database schema from:</p>";
        echo "<code>d:\\Xampp\\htdocs\\project test1\\database\\schema.sql</code>";
        echo "</div>";
    }
    
    // Show all users for debugging
    echo "<h3>All Users in Database:</h3>";
    $db->query("SELECT id, username, email, first_name, last_name, role, status FROM users ORDER BY created_at DESC");
    $users = $db->resultSet();
    
    if ($users) {
        echo "<table style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
        echo "<tr style='background: #007bff; color: white;'>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>ID</th>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Username</th>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Email</th>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Name</th>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Role</th>";
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Status</th>";
        echo "</tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $user->id . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $user->username . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $user->email . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $user->first_name . " " . $user->last_name . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'><span style='background: " . ($user->role === 'admin' ? '#dc3545' : ($user->role === 'counselor' ? '#17a2b8' : '#28a745')) . "; color: white; padding: 2px 8px; border-radius: 4px;'>" . ucfirst($user->role) . "</span></td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'><span style='background: " . ($user->status === 'active' ? '#28a745' : '#6c757d') . "; color: white; padding: 2px 8px; border-radius: 4px;'>" . ucfirst($user->status) . "</span></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>No users found in the database.</p>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>❌ Database Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<h4>Troubleshooting Steps:</h4>";
    echo "<ol>";
    echo "<li>Make sure XAMPP MySQL service is running</li>";
    echo "<li>Check if database 'career_counseling_platform' exists</li>";
    echo "<li>Import the schema: d:\\Xampp\\htdocs\\project test1\\database\\schema.sql</li>";
    echo "<li>Verify database credentials in app/config/config.php</li>";
    echo "</ol>";
    echo "</div>";
}
?>

<style>
    body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
    h2 { color: #007bff; }
    h3 { color: #333; margin-top: 30px; }
    code { background: #f8f9fa; padding: 2px 6px; border-radius: 4px; }
    a { color: #007bff; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>