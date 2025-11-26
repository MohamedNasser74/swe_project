<?php
session_start();

require_once __DIR__ . '/../app/config/config.php';

if (!isset($_SESSION['counselor_id'])) {
    if (isset($_SESSION['user_role'], $_SESSION['user_id']) && $_SESSION['user_role'] === 'counselor') {
        $_SESSION['counselor_id'] = (int) $_SESSION['user_id'];
    } else {
        header('Location: ' . APP_URL . '/auth/login');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: my_students.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$education = trim($_POST['education_level'] ?? '');
$specialization = trim($_POST['specialization'] ?? '');
$status = trim($_POST['status'] ?? 'active');
$notes = trim($_POST['notes'] ?? '');

$errors = [];

if ($name === '' || strlen($name) < 2) {
    $errors[] = 'Student name must be at least 2 characters.';
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please provide a valid email address.';
}

$allowedStatus = ['active', 'inactive', 'completed'];
if (!in_array($status, $allowedStatus, true)) {
    $status = 'active';
}

if (!empty($errors)) {
    $_SESSION['students_errors'] = $errors;
    header('Location: my_students.php');
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    $_SESSION['students_errors'] = ['Database connection failed: ' . $e->getMessage()];
    header('Location: my_students.php');
    exit;
}

$counselorId = (int) $_SESSION['counselor_id'];

try {
    $sql = "INSERT INTO students (counselor_id, name, email, phone, education_level, specialization, status, join_date, created_at, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE(), NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssssss', $counselorId, $name, $email, $phone, $education, $specialization, $status, $notes);
    $stmt->execute();
    $stmt->close();

    $_SESSION['students_success'] = 'Student added successfully.';
} catch (mysqli_sql_exception $e) {
    if ($conn->errno === 1062) {
        $_SESSION['students_errors'] = ['A student with this email already exists.'];
    } else {
        $_SESSION['students_errors'] = ['Unable to add student: ' . $e->getMessage()];
    }
}

header('Location: my_students.php');
exit;
