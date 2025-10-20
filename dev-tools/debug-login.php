<?php
/**
 * Login Debug Tool
 * Visit: http://localhost/project%20test1/public/debug-login.php
 */

// Include configuration
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

echo "<h2>🔍 Login Debug Tool</h2>";

$email = 'admin@careerplatform.com';
$password = 'admin123';

if (isset($_POST['test_login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
}

try {
    $db = new Database();
    
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>Testing Login For:</h3>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<p><strong>Password:</strong> " . htmlspecialchars($password) . "</p>";
    echo "</div>";
    
    // Step 1: Check if user exists
    $db->query("SELECT * FROM users WHERE email = :email");
    $db->bind(':email', $email);
    $user = $db->single();
    
    if (!$user) {
        echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h3>❌ Step 1 FAILED: User Not Found</h3>";
        echo "<p>No user found with email: " . htmlspecialchars($email) . "</p>";
        echo "</div>";
    } else {
        echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h3>✅ Step 1 PASSED: User Found</h3>";
        echo "<p><strong>ID:</strong> " . $user->id . "</p>";
        echo "<p><strong>Email:</strong> " . $user->email . "</p>";
        echo "<p><strong>Username:</strong> " . $user->username . "</p>";
        echo "<p><strong>Role:</strong> " . $user->role . "</p>";
        echo "<p><strong>Status:</strong> " . $user->status . "</p>";
        echo "</div>";
        
        // Step 2: Check status
        if ($user->status !== 'active') {
            echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h3>❌ Step 2 FAILED: Account Not Active</h3>";
            echo "<p>Account status: " . $user->status . "</p>";
            echo "</div>";
        } else {
            echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h3>✅ Step 2 PASSED: Account Active</h3>";
            echo "</div>";
            
            // Step 3: Test password
            echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h3>🧪 Step 3: Password Verification</h3>";
            echo "<p><strong>Stored Hash:</strong></p>";
            echo "<code style='font-size: 11px; word-break: break-all;'>" . $user->password . "</code>";
            echo "<p><strong>Testing Password:</strong> " . htmlspecialchars($password) . "</p>";
            echo "</div>";
            
            if (password_verify($password, $user->password)) {
                echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
                echo "<h3>✅ Step 3 PASSED: Password Correct!</h3>";
                echo "<p>The login should work. Try logging in again.</p>";
                echo "<p><a href='" . APP_URL . "/auth/login' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";
                echo "</div>";
            } else {
                echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
                echo "<h3>❌ Step 3 FAILED: Password Incorrect</h3>";
                echo "<p>The password verification failed.</p>";
                
                // Generate new hash
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                echo "<p><strong>New Hash for '" . htmlspecialchars($password) . "':</strong></p>";
                echo "<code style='font-size: 11px; word-break: break-all;'>" . $newHash . "</code>";
                
                echo "<form method='POST' style='margin-top: 15px;'>";
                echo "<input type='hidden' name='update_password' value='1'>";
                echo "<input type='hidden' name='new_hash' value='" . $newHash . "'>";
                echo "<button type='submit' style='background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Update Password Hash</button>";
                echo "</form>";
                echo "</div>";
            }
        }
    }
    
    // Handle password update
    if (isset($_POST['update_password'])) {
        $newHash = $_POST['new_hash'];
        $db->query("UPDATE users SET password = :password WHERE email = :email");
        $db->bind(':password', $newHash);
        $db->bind(':email', $email);
        
        if ($db->execute()) {
            echo "<div style='color: green; background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h3>✅ Password Updated Successfully!</h3>";
            echo "<p>You can now try logging in with: " . htmlspecialchars($email) . " / " . htmlspecialchars($password) . "</p>";
            echo "<p><a href='" . APP_URL . "/auth/login' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";
            echo "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div style='color: red; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>❌ Database Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
?>

<!-- Test Form -->
<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
    <h3>🧪 Test Different Credentials</h3>
    <form method='POST'>
        <div style='margin-bottom: 15px;'>
            <label><strong>Email:</strong></label><br>
            <input type='email' name='email' value='<?= htmlspecialchars($email) ?>' style='width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;'>
        </div>
        <div style='margin-bottom: 15px;'>
            <label><strong>Password:</strong></label><br>
            <input type='text' name='password' value='<?= htmlspecialchars($password) ?>' style='width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;'>
        </div>
        <button type='submit' name='test_login' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Test Login</button>
    </form>
</div>

<style>
    body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
    h2 { color: #007bff; }
    h3 { color: #333; margin-top: 30px; }
    code { background: #f8f9fa; padding: 2px 6px; border-radius: 4px; font-family: monospace; }
</style>