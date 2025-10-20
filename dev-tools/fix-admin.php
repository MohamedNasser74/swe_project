<?php
/**
 * Admin Password Fix Tool
 * Visit: http://localhost/project%20test1/public/fix-admin.php
 */

// Include configuration
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

echo "<h2>🔧 Admin Password Fix Tool</h2>";

try {
    $db = new Database();
    
    // Check if admin exists
    $db->query("SELECT * FROM users WHERE email = :email");
    $db->bind(':email', 'admin@careerplatform.com');
    $admin = $db->single();
    
    if (!$admin) {
        echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h3>❌ Admin User Not Found!</h3>";
        echo "<p>Creating admin user now...</p>";
        echo "</div>";
        
        // Create admin user
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $db->query("INSERT INTO users (username, email, password, first_name, last_name, role, email_verified, status) 
                    VALUES (:username, :email, :password, :first_name, :last_name, :role, :email_verified, :status)");
        $db->bind(':username', 'admin');
        $db->bind(':email', 'admin@careerplatform.com');
        $db->bind(':password', $hashedPassword);
        $db->bind(':first_name', 'System');
        $db->bind(':last_name', 'Administrator');
        $db->bind(':role', 'admin');
        $db->bind(':email_verified', 1);
        $db->bind(':status', 'active');
        
        if ($db->execute()) {
            echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h3>✅ Admin User Created Successfully!</h3>";
            echo "</div>";
        }
    } else {
        echo "<div style='color: blue; background: #d1ecf1; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h3>ℹ️ Admin User Found</h3>";
        echo "<p><strong>Email:</strong> " . $admin->email . "</p>";
        echo "<p><strong>Current Password Hash:</strong> " . substr($admin->password, 0, 20) . "...</p>";
        echo "</div>";
    }
    
    // Fix/Update the password
    if (isset($_POST['fix_password'])) {
        $newPassword = 'admin123';
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $db->query("UPDATE users SET password = :password WHERE email = :email");
        $db->bind(':password', $hashedPassword);
        $db->bind(':email', 'admin@careerplatform.com');
        
        if ($db->execute()) {
            echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h3>✅ Password Updated Successfully!</h3>";
            echo "<p>New password hash: " . substr($hashedPassword, 0, 30) . "...</p>";
            echo "</div>";
            
            // Verify the new password works
            if (password_verify('admin123', $hashedPassword)) {
                echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
                echo "<h3>✅ Password Verification Test PASSED!</h3>";
                echo "<p>You can now login with: admin@careerplatform.com / admin123</p>";
                echo "</div>";
            }
        }
    }
    
    // Test current password
    if ($admin) {
        echo "<h3>🧪 Password Test</h3>";
        $testPassword = 'admin123';
        
        if (password_verify($testPassword, $admin->password)) {
            echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h4>✅ Current Password Works!</h4>";
            echo "<p>The password 'admin123' is correct. Try logging in again.</p>";
            echo "<p><a href='" . APP_URL . "/auth/login' class='btn btn-success'>Go to Login Page</a></p>";
            echo "</div>";
        } else {
            echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h4>❌ Password Verification Failed</h4>";
            echo "<p>The stored password hash doesn't match 'admin123'.</p>";
            echo "<form method='POST' style='margin-top: 15px;'>";
            echo "<button type='submit' name='fix_password' class='btn btn-primary' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Fix Password Now</button>";
            echo "</form>";
            echo "</div>";
        }
    }
    
    // Show login instructions
    echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>🔐 Login Instructions</h3>";
    echo "<ol>";
    echo "<li><strong>Go to:</strong> <a href='" . APP_URL . "/auth/login' target='_blank'>" . APP_URL . "/auth/login</a></li>";
    echo "<li><strong>Email:</strong> <code>admin@careerplatform.com</code> (copy exactly)</li>";
    echo "<li><strong>Password:</strong> <code>admin123</code> (copy exactly)</li>";
    echo "<li><strong>Make sure:</strong> No extra spaces before or after</li>";
    echo "</ol>";
    echo "</div>";
    
    // Debug information
    echo "<h3>🔍 Debug Information</h3>";
    echo "<table style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
    echo "<tr style='background: #007bff; color: white;'>";
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Field</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Value</th>";
    echo "</tr>";
    
    if ($admin) {
        echo "<tr><td style='border: 1px solid #ddd; padding: 8px;'>Email</td><td style='border: 1px solid #ddd; padding: 8px;'>" . $admin->email . "</td></tr>";
        echo "<tr><td style='border: 1px solid #ddd; padding: 8px;'>Username</td><td style='border: 1px solid #ddd; padding: 8px;'>" . $admin->username . "</td></tr>";
        echo "<tr><td style='border: 1px solid #ddd; padding: 8px;'>Role</td><td style='border: 1px solid #ddd; padding: 8px;'>" . $admin->role . "</td></tr>";
        echo "<tr><td style='border: 1px solid #ddd; padding: 8px;'>Status</td><td style='border: 1px solid #ddd; padding: 8px;'>" . $admin->status . "</td></tr>";
        echo "<tr><td style='border: 1px solid #ddd; padding: 8px;'>Email Verified</td><td style='border: 1px solid #ddd; padding: 8px;'>" . ($admin->email_verified ? 'Yes' : 'No') . "</td></tr>";
        echo "<tr><td style='border: 1px solid #ddd; padding: 8px;'>Password Hash</td><td style='border: 1px solid #ddd; padding: 8px; font-family: monospace; font-size: 12px;'>" . $admin->password . "</td></tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>❌ Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
?>

<style>
    body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
    h2 { color: #007bff; }
    h3 { color: #333; margin-top: 30px; }
    code { background: #f8f9fa; padding: 2px 6px; border-radius: 4px; font-family: monospace; }
    a { color: #007bff; text-decoration: none; }
    a:hover { text-decoration: underline; }
    .btn { display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    .btn-success { background: #28a745; color: white; }
    .btn-primary { background: #007bff; color: white; }
</style>