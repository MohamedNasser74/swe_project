<?php
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../app/config/config.php';

if (!isset($_SESSION['counselor_id'])) {
    if (isset($_SESSION['user_role'], $_SESSION['user_id']) && $_SESSION['user_role'] === 'counselor') {
        $_SESSION['counselor_id'] = (int) $_SESSION['user_id'];
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

$counselorId = (int) $_SESSION['counselor_id'];

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed', 'detail' => $e->getMessage()]);
    exit;
}

$status = strtolower(trim($_GET['status'] ?? 'all'));
$search = trim($_GET['search'] ?? '');
$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo = trim($_GET['date_to'] ?? '');

$whereClauses = ['a.counselor_id = ?'];
$params = [$counselorId];
$types = 'i';

$validStatuses = ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show', 'pending'];
if ($status !== 'all' && in_array($status, $validStatuses, true)) {
    $whereClauses[] = 'a.status = ?';
    $params[] = $status;
    $types .= 's';
}

if ($search !== '') {
    $whereClauses[] = "CONCAT_WS(' ', u.first_name, u.last_name) LIKE ?";
    $params[] = '%' . $search . '%';
    $types .= 's';
}

$dateFromValid = DateTime::createFromFormat('Y-m-d', $dateFrom);
$dateToValid = DateTime::createFromFormat('Y-m-d', $dateTo);

if ($dateFrom !== '' && $dateFromValid && $dateFromValid->format('Y-m-d') === $dateFrom) {
    $whereClauses[] = 'a.appointment_date >= ?';
    $params[] = $dateFrom;
    $types .= 's';
}

if ($dateTo !== '' && $dateToValid && $dateToValid->format('Y-m-d') === $dateTo) {
    $whereClauses[] = 'a.appointment_date <= ?';
    $params[] = $dateTo;
    $types .= 's';
}

$sql = "SELECT a.id,
           a.student_id,
           CONCAT_WS(' ', u.first_name, u.last_name) AS student_name,
           a.appointment_date,
           a.appointment_time,
           a.duration AS duration_minutes,
           a.status,
           a.notes
    FROM appointments a
    LEFT JOIN users u ON u.id = a.student_id
    WHERE " . implode(' AND ', $whereClauses) . "
    ORDER BY a.appointment_date DESC, a.appointment_time DESC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $studentName = trim((string) ($row['student_name'] ?? ''));
        $row['student_display_name'] = $studentName !== '' ? $studentName : 'Student #' . (int) $row['student_id'];
        try {
            $row['formattedDate'] = (new DateTime($row['appointment_date'] . ' ' . $row['appointment_time']))->format('M d, Y — g:i A');
        } catch (Exception $e) {
            $row['formattedDate'] = trim($row['appointment_date'] . ' ' . $row['appointment_time']);
        }
        $row['duration_minutes'] = (int) ($row['duration_minutes'] ?? 0);
        $data[] = $row;
    }
    $stmt->close();
    echo json_encode(['appointments' => $data]);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch appointments', 'detail' => $e->getMessage()]);
}
