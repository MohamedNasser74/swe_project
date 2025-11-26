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
    header('Location: view_appointments.php');
    exit;
}

$counselorId = (int) $_SESSION['counselor_id'];
$appointmentId = isset($_POST['appointment_id']) ? (int) $_POST['appointment_id'] : 0;
$newStatus = strtolower(trim($_POST['status'] ?? ''));

$validStatuses = ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show', 'pending'];
$statusLabels = [
    'scheduled' => 'Scheduled',
    'confirmed' => 'Confirmed',
    'completed' => 'Completed',
    'cancelled' => 'Cancelled',
    'no_show' => 'No-Show',
    'pending' => 'Pending'
];

$readableStatus = $statusLabels[$newStatus] ?? ucfirst(str_replace('_', ' ', $newStatus));

if ($appointmentId <= 0 || !in_array($newStatus, $validStatuses, true)) {
    $_SESSION['appointments_errors'] = ['Invalid appointment or status provided.'];
    header('Location: view_appointments.php');
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    $_SESSION['appointments_errors'] = ['Database connection failed: ' . $e->getMessage()];
    header('Location: view_appointments.php');
    exit;
}

try {
    $conn->begin_transaction();

    $checkSql = 'SELECT status FROM appointments WHERE id = ? AND counselor_id = ? FOR UPDATE';
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param('ii', $appointmentId, $counselorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
    $stmt->close();

    if (!$appointment) {
        $conn->rollback();
        $_SESSION['appointments_errors'] = ['Appointment not found or access denied.'];
        header('Location: view_appointments.php');
        exit;
    }

    if ($appointment['status'] === $newStatus) {
        $conn->rollback();
        $_SESSION['appointments_success'] = 'Appointment is already marked as ' . $readableStatus . '.';
        header('Location: view_appointments.php');
        exit;
    }

    $updateSql = 'UPDATE appointments SET status = ?, updated_at = NOW() WHERE id = ? AND counselor_id = ?';
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param('sii', $newStatus, $appointmentId, $counselorId);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    $_SESSION['appointments_success'] = 'Appointment status updated successfully to ' . $readableStatus . '.';
} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    $_SESSION['appointments_errors'] = ['Unable to update appointment: ' . $e->getMessage()];
}

header('Location: view_appointments.php');
exit;
