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

$appointmentId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($appointmentId <= 0) {
    header('Location: view_appointments.php');
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
        $sql = "SELECT a.*,
               CONCAT_WS(' ', su.first_name, su.last_name) AS student_name,
               su.email AS student_email,
               su.phone AS student_phone,
               CONCAT_WS(' ', cu.first_name, cu.last_name) AS counselor_name,
               cu.email AS counselor_email,
               a.duration AS duration_minutes
            FROM appointments a
            LEFT JOIN users su ON su.id = a.student_id
            LEFT JOIN users cu ON cu.id = a.counselor_id
            WHERE a.id = ? AND a.counselor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $appointmentId, $counselorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    die('Unable to fetch appointment: ' . htmlspecialchars($e->getMessage()));
}

if (!$appointment) {
    header('Location: view_appointments.php');
    exit;
}

$studentDisplayName = trim((string) ($appointment['student_name'] ?? ''));
if ($studentDisplayName === '') {
    $studentDisplayName = 'Student #' . (int) $appointment['student_id'];
}

$counselorDisplayName = trim((string) ($appointment['counselor_name'] ?? ''));
if ($counselorDisplayName === '') {
    $counselorDisplayName = 'You';
}

$durationMinutes = isset($appointment['duration_minutes']) && $appointment['duration_minutes'] !== null
    ? (int) $appointment['duration_minutes']
    : (isset($appointment['duration']) ? (int) $appointment['duration'] : 0);

$statusPresentation = [
    'scheduled' => ['label' => 'Scheduled', 'bg' => '#e0f2fe', 'color' => '#0369a1'],
    'confirmed' => ['label' => 'Confirmed', 'bg' => '#ede9fe', 'color' => '#5b21b6'],
    'completed' => ['label' => 'Completed', 'bg' => '#dcfce7', 'color' => '#15803d'],
    'pending' => ['label' => 'Pending', 'bg' => '#fef3c7', 'color' => '#b45309'],
    'cancelled' => ['label' => 'Cancelled', 'bg' => '#fee2e2', 'color' => '#b91c1c'],
    'no_show' => ['label' => 'No-Show', 'bg' => '#fef2f2', 'color' => '#9f1239']
];

$statusKey = (string) ($appointment['status'] ?? '');
$statusConfig = $statusPresentation[$statusKey] ?? null;
$statusLabel = $statusConfig['label'] ?? ucfirst(str_replace('_', ' ', $statusKey ?: 'Unknown'));
$statusBg = $statusConfig['bg'] ?? '#e2e8f0';
$statusColor = $statusConfig['color'] ?? '#475569';

function safe(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function friendlyDateTime(?string $date, ?string $time): string
{
    if (!$date) {
        return '—';
    }

    try {
        $dt = new DateTime(trim($date . ' ' . ($time ?? '00:00:00')));
        return $dt->format('l, F j, Y \a\t g:i A');
    } catch (Exception) {
        return safe(trim(($date ?? '') . ' ' . ($time ?? '')));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-bxPWQs2Kq8np5xpoE2mR7BfrpsbR9f7D3Dveoxu48UUfZo0fo0RKZ77N8BINU3CFAaj6MqFmoVoe2MktKL7Xlw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary: #1d4ed8;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            min-height: 100vh;
        }
        header {
            padding: 1.75rem;
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.92), rgba(37, 99, 235, 0.95));
            color: white;
            text-align: center;
        }
        header h1 { margin: 0; font-size: 2rem; font-weight: 700; }
        main {
            max-width: 900px;
            margin: -2.5rem auto 3rem;
            padding: 0 1.25rem;
        }
        .card {
            background: var(--card);
            border-radius: 18px;
            padding: 1.75rem;
            box-shadow: 0 18px 40px -24px rgba(15, 23, 42, 0.38);
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem;
            background: #f8fafc;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        .detail-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--muted);
        }
        .detail-value {
            font-size: 1rem;
            font-weight: 600;
        }
        .notes {
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem;
            min-height: 120px;
            background: #fdfdfd;
            line-height: 1.6;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.05rem;
            border-radius: 12px;
            margin-top: 1.75rem;
            background: #e0e7ff;
            color: #312e81;
            text-decoration: none;
            font-weight: 600;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
<header>
    <h1>Appointment Details</h1>
</header>

<main>
    <article class="card">
        <section>
            <div class="section-title">Overview</div>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Student</span>
                    <span class="detail-value"><?= safe($studentDisplayName) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date &amp; Time</span>
                    <span class="detail-value"><?= friendlyDateTime($appointment['appointment_date'], $appointment['appointment_time']) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Duration</span>
                    <span class="detail-value"><?= $durationMinutes ?> minutes</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="status-badge" style="background: <?= $statusBg ?>; color: <?= $statusColor ?>;">
                        <i class="fas fa-circle" style="font-size: 0.55rem;"></i>
                        <?= safe($statusLabel) ?>
                    </span>
                </div>
            </div>
        </section>

        <section>
            <div class="section-title">Student Contact</div>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value"><?= safe($appointment['student_email'] ?? '—') ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value"><?= safe($appointment['student_phone'] ?? '—') ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Counselor</span>
                    <span class="detail-value"><?= safe($counselorDisplayName) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">
                        <?php
                        try {
                            $created = new DateTime($appointment['created_at']);
                            echo $created->format('M d, Y g:i A');
                        } catch (Exception) {
                            echo safe($appointment['created_at']);
                        }
                        ?>
                    </span>
                </div>
            </div>
        </section>

        <section>
            <div class="section-title">Notes</div>
            <div class="notes">
                <?= $appointment['notes'] ? nl2br(safe($appointment['notes'])) : '<em>No notes provided.</em>' ?>
            </div>
        </section>

        <a href="view_appointments.php" class="btn-back"><i class="fas fa-arrow-left"></i>Back to appointments</a>
    </article>
</main>
</body>
</html>
