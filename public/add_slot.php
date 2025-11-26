<?php
session_start();

require_once __DIR__ . '/../app/config/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    $_SESSION['schedule_errors'] = ['Could not connect to the database: ' . $e->getMessage()];
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

$date = trim($_POST['date'] ?? '');
$startTime = trim($_POST['start_time'] ?? '');
$endTime = trim($_POST['end_time'] ?? '');
$duration = (int) ($_POST['duration'] ?? 30);

$errors = [];
$validDurations = [30, 45, 60];

if ($date === '') {
    $errors[] = 'Please select a date for the time slot.';
} else {
    $slotDate = DateTime::createFromFormat('Y-m-d', $date);
    $slotDateValid = $slotDate && $slotDate->format('Y-m-d') === $date;
    if (!$slotDateValid) {
        $errors[] = 'Invalid date format provided.';
    } else {
        $today = new DateTime('today');
        if ($slotDate < $today) {
            $errors[] = 'Choose a date that has not already passed.';
        }
    }
}

$startDateTime = DateTime::createFromFormat('H:i', $startTime);
$endDateTime = DateTime::createFromFormat('H:i', $endTime);

if (!$startDateTime) {
    $errors[] = 'Provide a valid start time.';
}
if (!$endDateTime) {
    $errors[] = 'Provide a valid end time.';
}

if ($startDateTime && $endDateTime) {
    if ($endDateTime <= $startDateTime) {
        $errors[] = 'End time must be later than start time.';
    } else {
        $diffMinutes = ($endDateTime->getTimestamp() - $startDateTime->getTimestamp()) / 60;
        if ($diffMinutes < 30) {
            $errors[] = 'Each time slot must be at least 30 minutes long.';
        }
        if (!in_array($duration, $validDurations, true)) {
            $errors[] = 'Select a valid duration option.';
        }
        if ($diffMinutes < $duration) {
            $errors[] = 'The selected duration exceeds the total length of this slot.';
        }

        if (isset($slotDate)) {
            $startWithDate = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $startDateTime->format('H:i'));
            $now = new DateTime();
            if ($startWithDate && $startWithDate <= $now) {
                $errors[] = 'Select a start time that is in the future.';
            }
        }
    }
}

$_SESSION['schedule_old'] = [
    'date' => $date,
    'start_time' => $startTime,
    'end_time' => $endTime,
    'duration' => $duration
];

if (empty($errors) && isset($slotDate, $startDateTime, $endDateTime)) {
    try {
        $stmt = $conn->prepare(
            'SELECT COUNT(*) AS overlap_count
             FROM counselor_schedule
             WHERE counselor_id = ?
               AND date = ?
               AND start_time < ?
               AND end_time > ?'
        );
        $newStart = $startDateTime->format('H:i:s');
        $newEnd = $endDateTime->format('H:i:s');
        $stmt->bind_param('isss', $counselorId, $date, $newEnd, $newStart);
        $stmt->execute();
        $overlapCount = $stmt->get_result()->fetch_assoc()['overlap_count'] ?? 0;
        $stmt->close();

        if ($overlapCount > 0) {
            $errors[] = 'This time slot overlaps with an existing availability entry.';
        }
    } catch (mysqli_sql_exception $e) {
        $errors[] = 'Failed to check for overlapping slots: ' . $e->getMessage();
    }
}

if (!empty($errors)) {
    $_SESSION['schedule_errors'] = $errors;
    header('Location: manage_schedule.php');
    exit;
}

try {
    $stmt = $conn->prepare(
        'INSERT INTO counselor_schedule (counselor_id, date, start_time, end_time, duration_minutes, is_available)
         VALUES (?, ?, ?, ?, ?, 1)'
    );
    $stmt->bind_param(
        'isssi',
        $counselorId,
        $date,
        $startDateTime->format('H:i:s'),
        $endDateTime->format('H:i:s'),
        $duration
    );
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['schedule_old']);
    $_SESSION['schedule_success'] = 'Time slot created successfully.';
} catch (mysqli_sql_exception $e) {
    $_SESSION['schedule_errors'] = ['Unable to save the time slot: ' . $e->getMessage()];
}

header('Location: manage_schedule.php');
exit;
