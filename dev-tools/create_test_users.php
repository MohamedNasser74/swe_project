<?php
// Simple script to create test accounts
require_once '../config/config.php';
require_once '../app/core/Database.php';

try {
    $db = new Database();
    
    // Check if admin user exists
    $db->query("SELECT id FROM users WHERE email = 'admin@example.com'");
    if (!$db->single()) {
        // Create admin user
        $db->query("INSERT INTO users (first_name, last_name, email, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $db->bind(1, 'Admin');
        $db->bind(2, 'User');
        $db->bind(3, 'admin@example.com');
        $db->bind(4, password_hash('admin123', PASSWORD_DEFAULT));
        $db->bind(5, 'admin');
        $db->bind(6, 'active');
        $db->bind(7, date('Y-m-d H:i:s'));
        $db->execute();
        echo "Admin user created successfully!<br>";
    } else {
        echo "Admin user already exists.<br>";
    }
    
    // Check if student user exists
    $db->query("SELECT id FROM users WHERE email = 'student@example.com'");
    if (!$db->single()) {
        // Create student user
        $db->query("INSERT INTO users (first_name, last_name, email, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $db->bind(1, 'John');
        $db->bind(2, 'Student');
        $db->bind(3, 'student@example.com');
        $db->bind(4, password_hash('student123', PASSWORD_DEFAULT));
        $db->bind(5, 'student');
        $db->bind(6, 'active');
        $db->bind(7, date('Y-m-d H:i:s'));
        $db->execute();
        echo "Student user created successfully!<br>";
    } else {
        echo "Student user already exists.<br>";
    }
    
    // Check if counselor user exists
    $db->query("SELECT id FROM users WHERE email = 'counselor@example.com'");
    if (!$db->single()) {
        // Create counselor user
        $db->query("INSERT INTO users (first_name, last_name, email, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $db->bind(1, 'Jane');
        $db->bind(2, 'Counselor');
        $db->bind(3, 'counselor@example.com');
        $db->bind(4, password_hash('counselor123', PASSWORD_DEFAULT));
        $db->bind(5, 'counselor');
        $db->bind(6, 'active');
        $db->bind(7, date('Y-m-d H:i:s'));
        $db->execute();
        echo "Counselor user created successfully!<br>";
    } else {
        echo "Counselor user already exists.<br>";
    }
    
    echo "<br><strong>Test accounts are ready!</strong><br>";
    echo "<strong>Login Credentials:</strong><br>";
    echo "Admin: admin@example.com / admin123<br>";
    echo "Student: student@example.com / student123<br>";
    echo "Counselor: counselor@example.com / counselor123<br>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>