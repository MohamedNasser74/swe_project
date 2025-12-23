<?php
// Fix for missing status column in users table
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

echo "Attempting to fix database schema...\n";

try {
    $db = new Database();
    
    // Check if column exists
    $sql = "SHOW COLUMNS FROM users LIKE 'status'";
    $db->query($sql);
    $result = $db->single();
    
    if ($result) {
        echo "Column 'status' already exists in 'users' table.\n";
    } else {
        echo "Column 'status' missing. Adding it now...\n";
        $sql = "ALTER TABLE users ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' AFTER email_verified";
        $db->query($sql);
        $db->execute();
        echo "Successfully added 'status' column to 'users' table!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
