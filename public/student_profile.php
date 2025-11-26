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
    $sql = "SELECT s.*, COALESCE(s.profile_image, '') AS profile_image
            FROM students s
            WHERE s.id = ? AND s.counselor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $studentId, $counselorId);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    die('Unable to load student: ' . htmlspecialchars($e->getMessage()));
}

if (!$student) {
    header('Location: my_students.php');
    exit;
}

$sessionStats = [
    'total' => 0,
    'scheduled' => 0,
    'completed' => 0,
    'cancelled' => 0,
    'upcoming' => 0
];
$recentSessions = [];

try {
    $statSql = "SELECT status, COUNT(*) AS total
                FROM appointments
                WHERE student_id = ? AND counselor_id = ?
                GROUP BY status";
    $stmt = $conn->prepare($statSql);
    $stmt->bind_param('ii', $studentId, $counselorId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $status = strtolower($row['status']);
        $count = (int) $row['total'];
        $sessionStats['total'] += $count;
        if (array_key_exists($status, $sessionStats)) {
            $sessionStats[$status] = $count;
        }
        if (in_array($status, ['scheduled', 'confirmed', 'pending'], true)) {
            $sessionStats['upcoming'] += $count;
        }
    }
    $stmt->close();

    $recentSql = "SELECT id, appointment_date, appointment_time, status, notes
                  FROM appointments
                  WHERE student_id = ? AND counselor_id = ?
                  ORDER BY appointment_date DESC, appointment_time DESC
                  LIMIT 6";
    $stmt = $conn->prepare($recentSql);
    $stmt->bind_param('ii', $studentId, $counselorId);
    $stmt->execute();
    $recentSessions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    // proceed with empty stats
}

function safe(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDateTime(?string $date, ?string $time = null): string
{
    if (!$date) {
        return '—';
    }
    try {
        $dt = new DateTime(trim($date . ' ' . ($time ?? '00:00:00')));
        return $dt->format('M d, Y \a\t g:i A');
    } catch (Exception) {
        return htmlspecialchars(trim(($date ?? '') . ' ' . ($time ?? '')));
    }
}

function formatStatus(string $status): string
{
    $map = [
        'scheduled' => 'Scheduled',
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'pending' => 'Pending',
        'no_show' => 'No-Show'
    ];
    return $map[strtolower($status)] ?? ucfirst($status);
}

function statusBadgeClass(string $status): string
{
    return match (strtolower($status)) {
        'completed' => 'status-completed',
        'cancelled' => 'status-cancelled',
        'pending' => 'status-pending',
        'confirmed' => 'status-confirmed',
        default => 'status-default'
    };
}

$avatarSource = $student['profile_image'] ? (filter_var($student['profile_image'], FILTER_VALIDATE_URL) ? $student['profile_image'] : APP_URL . '/images/students/' . $student['profile_image']) : '';

$initials = '';
if (!empty($student['name'])) {
    $parts = preg_split('/\s+/', trim($student['name']));
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | <?= safe($student['name'] ?? 'Student') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-bxPWQs2Kq8np5xpoE2mR7BfrpsbR9f7D3Dveoxu48UUfZo0fo0RKZ77N8BINU3CFAaj6MqFmoVoe2MktKL7Xlw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            font-family: 'Inter', sans-serif;
            color: var(--text);
        }
        header {
            padding: 2.8rem 1.5rem 2rem;
            background: radial-gradient(circle at top left, rgba(37,99,235,0.75), rgba(29,78,216,0.95));
            color: #fff;
        }
        header a { color: #fff; text-decoration: none; font-weight: 600; }
        .back-link { display: inline-flex; align-items: center; gap: 0.45rem; }
        .profile-header {
            display: flex;
            gap: 2rem;
            align-items: center;
            margin-top: 1.8rem;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 30px;
            overflow: hidden;
            border: 4px solid rgba(255,255,255,0.35);
        }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-avatar-fallback {
            width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,0.2);
            font-size: 2.6rem;
            font-weight: 700;
        }
        .profile-info h1 { margin: 0; font-size: 2rem; }
        .profile-info p { margin: 0.2rem 0; opacity: 0.85; }
        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            font-weight: 600;
            background: rgba(255,255,255,0.2);
        }
        main {
            max-width: 1140px;
            margin: -2.5rem auto 3rem;
            padding: 0 1.5rem;
        }
        .card {
            background: var(--card);
            border-radius: 18px;
            box-shadow: 0 24px 48px -28px rgba(15,23,42,0.35);
            padding: 1.7rem;
            border: 1px solid rgba(148,163,184,0.15);
        }
        .grid {
            display: grid;
            gap: 1.4rem;
        }
        .grid-cols-2 { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
        .section-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 1.1rem; }
        .details-list {
            display: grid;
            gap: 0.8rem;
        }
        .details-list span { display: block; }
        .label { font-size: 0.85rem; color: var(--secondary); text-transform: uppercase; letter-spacing: 0.05em; }
        .value { font-size: 1rem; font-weight: 600; }
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }
        .stat-card {
            background: rgba(37, 99, 235, 0.06);
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
        }
        .stat-card h3 { margin: 0; font-size: 2rem; color: var(--primary); }
        .stat-card p { margin: 0.3rem 0 0; color: var(--secondary); font-weight: 600; }
        .sessions-list { display: grid; gap: 0.8rem; }
        .session-item {
            border: 1px solid rgba(148,163,184,0.2);
            border-radius: 14px;
            padding: 1rem;
            display: grid;
            gap: 0.35rem;
            background: rgba(248,250,252,0.6);
        }
        .session-top { display: flex; justify-content: space-between; align-items: center; }
        .status-completed { background: rgba(16,185,129,0.18); color: var(--success); }
        .status-cancelled { background: rgba(239,68,68,0.18); color: var(--danger); }
        .status-pending { background: rgba(245,158,11,0.18); color: var(--warning); }
        .status-confirmed { background: rgba(37,99,235,0.18); color: var(--primary); }
        .status-default { background: rgba(148,163,184,0.18); color: var(--secondary); }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .actions { margin-top: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.75rem; }
        .btn {
            border: none;
            border-radius: 12px;
            padding: 0.65rem 1.1rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 12px 24px -16px rgba(15,23,42,0.25); }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-secondary { background: rgba(148,163,184,0.2); color: var(--secondary); }
        .notes {
            background: rgba(37,99,235,0.05);
            border-radius: 14px;
            padding: 1rem;
            min-height: 120px;
            line-height: 1.6;
        }
        @media (max-width: 768px) {
            header { padding: 2.4rem 1rem 2rem; }
            main { padding: 0 1rem; }
            .profile-header { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
<header>
    <a href="my_students.php" class="back-link"><i class="fas fa-arrow-left"></i>Back to My Students</a>
    <div class="profile-header">
        <div class="profile-avatar">
            <?php if ($avatarSource): ?>
                <img src="<?= safe($avatarSource) ?>" alt="<?= safe($student['name'] ?? 'Student') ?>">
            <?php else: ?>
                <div class="profile-avatar-fallback"><?= safe($initials ?: 'ST') ?></div>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h1><?= safe($student['name'] ?? 'Student') ?></h1>
            <p><?= safe($student['education_level'] ?? 'Education not provided') ?></p>
            <p><i class="fas fa-envelope"></i> <?= safe($student['email'] ?? 'No email') ?></p>
            <p><i class="fas fa-phone"></i> <?= safe($student['phone'] ?? 'No phone') ?></p>
            <span class="status-chip">
                <i class="fas fa-circle"></i>
                <?= ucfirst(strtolower($student['status'] ?? 'active')) ?> student
            </span>
        </div>
    </div>
</header>

<main>
    <section class="grid grid-cols-2">
        <article class="card">
            <h2 class="section-title">Student Overview</h2>
            <div class="details-list">
                <div>
                    <span class="label">Specialization</span>
                    <span class="value"><?= safe($student['specialization'] ?? 'Not specified') ?></span>
                </div>
                <div>
                    <span class="label">Join Date</span>
                    <span class="value"><?= formatDateTime($student['join_date'] ?? null, null) ?></span>
                </div>
                <div>
                    <span class="label">Last Session</span>
                    <span class="value"><?= formatDateTime($student['last_session_date'] ?? null, null) ?></span>
                </div>
                <div>
                    <span class="label">Total Sessions</span>
                    <span class="value"><?= (int) ($student['total_sessions'] ?? 0) ?></span>
                </div>
            </div>
            <div class="actions">
                <a href="mailto:<?= safe($student['email'] ?? '') ?>" class="btn btn-secondary"><i class="fas fa-envelope"></i>Email</a>
                <a href="student_sessions.php?id=<?= (int) $student['id'] ?>" class="btn btn-primary"><i class="fas fa-calendar"></i>View Sessions</a>
                <a href="update_student.php?id=<?= (int) $student['id'] ?>" class="btn btn-secondary"><i class="fas fa-edit"></i>Edit Profile</a>
            </div>
        </article>
        <article class="card">
            <h2 class="section-title">Session Statistics</h2>
            <div class="stats-row">
                <div class="stat-card">
                    <h3><?= $sessionStats['total'] ?></h3>
                    <p>Total Sessions</p>
                </div>
                <div class="stat-card" style="background: rgba(16,185,129,0.12); color: var(--success);">
                    <h3><?= $sessionStats['completed'] ?></h3>
                    <p>Completed</p>
                </div>
                <div class="stat-card" style="background: rgba(37,99,235,0.12); color: var(--primary);">
                    <h3><?= $sessionStats['upcoming'] ?></h3>
                    <p>Upcoming</p>
                </div>
                <div class="stat-card" style="background: rgba(239,68,68,0.12); color: var(--danger);">
                    <h3><?= $sessionStats['cancelled'] ?></h3>
                    <p>Cancelled</p>
                </div>
            </div>
        </article>
    </section>

    <section class="card" style="margin-top: 1.6rem;">
        <h2 class="section-title">Recent Sessions</h2>
        <?php if (!empty($recentSessions)): ?>
            <div class="sessions-list">
                <?php foreach ($recentSessions as $session): ?>
                    <div class="session-item">
                        <div class="session-top">
                            <strong><?= formatDateTime($session['appointment_date'], $session['appointment_time']) ?></strong>
                            <span class="badge <?= statusBadgeClass($session['status']) ?>">
                                <i class="fas fa-circle" style="font-size: 0.45rem;"></i>
                                <?= formatStatus($session['status']) ?>
                            </span>
                        </div>
                        <?php if (!empty($session['notes'])): ?>
                            <p style="margin: 0; color: var(--secondary);">Notes: <?= safe($session['notes']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: var(--secondary);">No sessions recorded yet. Schedule the first meeting to track progress.</p>
        <?php endif; ?>
    </section>

    <section class="card" style="margin-top: 1.6rem;">
        <h2 class="section-title">Counselor Notes</h2>
        <div class="notes">
            <?= $student['notes'] ? nl2br(safe($student['notes'])) : '<em>No counselor notes yet.</em>' ?>
        </div>
    </section>
</main>
</body>
</html>
