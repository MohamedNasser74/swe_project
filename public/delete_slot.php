<?php
session_start();

require_once __DIR__ . '/../app/config/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    $_SESSION['schedule_errors'] = ['Database connection failed: ' . $e->getMessage()];
    header('Location: manage_schedule.php');
    exit;
}

if (!isset($_SESSION['counselor_id'])) {
    if (isset($_SESSION['user_role'], $_SESSION['user_id']) && $_SESSION['user_role'] === 'counselor') {
        $_SESSION['counselor_id'] = (int) $_SESSION['user_id'];
    } else {
        header('Location: ' . APP_URL . '/auth/login');
        exit;
    }
}

$counselorId = (int) $_SESSION['counselor_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_schedule.php');
    exit;
}

$slotId = isset($_POST['slot_id']) ? (int) $_POST['slot_id'] : 0;

if ($slotId < 1) {
    $_SESSION['schedule_errors'] = ['Invalid time slot selected.'];
    header('Location: manage_schedule.php');
    exit;
}

try {
    $stmt = $conn->prepare('DELETE FROM counselor_schedule WHERE id = ? AND counselor_id = ?');
    $stmt->bind_param('ii', $slotId, $counselorId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['schedule_success'] = 'Time slot removed successfully.';
    } else {
        $_SESSION['schedule_errors'] = ['You can only delete your own future time slots.'];
    }

    $stmt->close();
} catch (mysqli_sql_exception $e) {
    $_SESSION['schedule_errors'] = ['Unable to remove the time slot: ' . $e->getMessage()];
}

header('Location: manage_schedule.php');
exit;
