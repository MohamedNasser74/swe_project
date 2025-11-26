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

$studentId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($studentId <= 0) {
    header('Location: my_students.php');
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}

$counselorId = (int) $_SESSION['counselor_id'];

try {
    $studentSql = "SELECT name, email
                   FROM students
                   WHERE id = ? AND counselor_id = ?";
    $stmt = $conn->prepare($studentSql);
    $stmt->bind_param('ii', $studentId, $counselorId);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$student) {
        header('Location: my_students.php');
        exit;
    }

    $sessionsSql = "SELECT id, appointment_date, appointment_time, duration, status, notes
                    FROM appointments
                    WHERE student_id = ? AND counselor_id = ?
                    ORDER BY appointment_date DESC, appointment_time DESC";
    $stmt = $conn->prepare($sessionsSql);
    $stmt->bind_param('ii', $studentId, $counselorId);
    $stmt->execute();
    $sessions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    die('Unable to load sessions: ' . htmlspecialchars($e->getMessage()));
}

function safe(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDateTime(?string $date, ?string $time): string
{
    if (!$date) {
        return '—';
    }
    try {
        $dt = new DateTime(trim($date . ' ' . ($time ?? '00:00:00')));
        return $dt->format('M d, Y — g:i A');
    } catch (Exception) {
        return htmlspecialchars(trim(($date ?? '') . ' ' . ($time ?? '')));
    }
}

function statusClass(string $status): string
{
    return match (strtolower($status)) {
        'completed' => 'badge-success',
        'cancelled' => 'badge-danger',
        'pending' => 'badge-warning',
        'confirmed' => 'badge-primary',
        default => 'badge-muted'
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session History | <?= safe($student['name']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-bxPWQs2Kq8np5xpoE2mR7BfrpsbR9f7D3Dveoxu48UUfZo0fo0RKZ77N8BINU3CFAaj6MqFmoVoe2MktKL7Xlw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #64748b;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            font-family: 'Inter', sans-serif;
            color: #0f172a;
        }
        header {
            padding: 2.4rem 1.5rem 1.8rem;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.92), rgba(29, 78, 216, 0.95));
            color: #fff;
        }
        header a { color: #fff; text-decoration: none; font-weight: 600; }
        main {
            max-width: 980px;
            margin: -2rem auto 3rem;
            padding: 0 1.5rem;
        }
        .card {
            background: var(--card);
            border-radius: 18px;
            padding: 1.75rem;
            box-shadow: 0 22px 44px -28px rgba(15,23,42,0.35);
            border: 1px solid rgba(148,163,184,0.12);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead { background: rgba(37,99,235,0.08); }
        th, td {
            padding: 0.85rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
        }
        tbody tr:hover { background: rgba(248,250,252,0.7); }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.28rem 0.7rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .badge-success { background: rgba(16,185,129,0.18); color: var(--success); }
        .badge-danger { background: rgba(239,68,68,0.18); color: var(--danger); }
        .badge-warning { background: rgba(245,158,11,0.18); color: var(--warning); }
        .badge-primary { background: rgba(37,99,235,0.18); color: var(--primary); }
        .badge-muted { background: rgba(148,163,184,0.18); color: var(--secondary); }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--secondary);
        }
        @media (max-width: 768px) {
            main { padding: 0 1rem; }
            table, thead, tbody, th, td, tr { display: block; }
            thead { display: none; }
            tr { margin-bottom: 1rem; border: 1px solid rgba(148,163,184,0.15); border-radius: 14px; padding: 1rem; }
            td { border: none; padding: 0.35rem 0; }
            td::before { content: attr(data-label); font-weight: 600; display: block; color: var(--secondary); margin-bottom: 0.2rem; }
        }
    </style>
</head>
<body>
<header>
    <a href="student_profile.php?id=<?= $studentId ?>"><i class="fas fa-arrow-left"></i> Back to Profile</a>
    <h1 style="margin-top: 1rem;">Session History</h1>
    <p><?= safe($student['name']) ?> &middot; <?= safe($student['email']) ?></p>
</header>

<main>
    <section class="card">
        <?php if (!empty($sessions)): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Date &amp; Time</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessions as $session): ?>
                            <tr>
                                <td data-label="Date & Time"><?= formatDateTime($session['appointment_date'], $session['appointment_time']) ?></td>
                                <td data-label="Duration"><?= (int) ($session['duration'] ?? 0) ?> minutes</td>
                                <td data-label="Status">
                                    <span class="badge <?= statusClass($session['status'] ?? '') ?>">
                                        <i class="fas fa-circle" style="font-size: 0.45rem;"></i>
                                        <?= safe(ucfirst(str_replace('_', ' ', $session['status'] ?? ''))) ?>
                                    </span>
                                </td>
                                <td data-label="Notes"><?= $session['notes'] ? safe($session['notes']) : '<span style="color: var(--secondary);">No notes</span>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times fa-3x" style="margin-bottom: 1rem;"></i>
                <p>No sessions recorded yet for this student.</p>
            </div>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
