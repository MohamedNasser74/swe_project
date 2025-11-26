<?php
session_start();

require_once __DIR__ . '/../app/config/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
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

$successMessage = $_SESSION['appointments_success'] ?? '';
$errorMessages = $_SESSION['appointments_errors'] ?? [];
unset($_SESSION['appointments_success'], $_SESSION['appointments_errors']);

$statusDefinitions = [
    'scheduled' => [
        'label' => 'Scheduled',
        'badgeBg' => '#e0f2fe',
        'badgeColor' => '#0369a1',
        'icon' => 'fas fa-hourglass-half',
        'statColor' => '#0369a1'
    ],
    'confirmed' => [
        'label' => 'Confirmed',
        'badgeBg' => '#ede9fe',
        'badgeColor' => '#5b21b6',
        'icon' => 'fas fa-calendar-day',
        'statColor' => '#5b21b6'
    ],
    'completed' => [
        'label' => 'Completed',
        'badgeBg' => '#dcfce7',
        'badgeColor' => '#15803d',
        'icon' => 'fas fa-check-circle',
        'statColor' => '#15803d'
    ],
    'pending' => [
        'label' => 'Pending',
        'badgeBg' => '#fef3c7',
        'badgeColor' => '#b45309',
        'icon' => 'fas fa-clock',
        'statColor' => '#b45309'
    ],
    'cancelled' => [
        'label' => 'Cancelled',
        'badgeBg' => '#fee2e2',
        'badgeColor' => '#b91c1c',
        'icon' => 'fas fa-times-circle',
        'statColor' => '#b91c1c'
    ],
    'no_show' => [
        'label' => 'No-Show',
        'badgeBg' => '#fef2f2',
        'badgeColor' => '#9f1239',
        'icon' => 'fas fa-user-slash',
        'statColor' => '#9f1239'
    ]
];

$statusOptions = ['all' => 'All Statuses'];
foreach ($statusDefinitions as $key => $definition) {
    $statusOptions[$key] = $definition['label'];
}
$statusesAllowed = array_keys($statusOptions);

$statusFilter = strtolower(trim($_GET['status'] ?? 'all'));
$statusFilter = in_array($statusFilter, $statusesAllowed, true) ? $statusFilter : 'all';

$searchQuery = trim($_GET['search'] ?? '');
$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo = trim($_GET['date_to'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$whereClauses = ['a.counselor_id = ?'];
$params = [$counselorId];
$paramTypes = ['i'];

if ($statusFilter !== 'all') {
    $whereClauses[] = 'a.status = ?';
    $params[] = $statusFilter;
    $paramTypes[] = 's';
}

if ($searchQuery !== '') {
    $whereClauses[] = "CONCAT_WS(' ', u.first_name, u.last_name) LIKE ?";
    $params[] = '%' . $searchQuery . '%';
    $paramTypes[] = 's';
}

$dateFromValid = DateTime::createFromFormat('Y-m-d', $dateFrom);
$dateToValid = DateTime::createFromFormat('Y-m-d', $dateTo);

if ($dateFrom !== '' && (!$dateFromValid || $dateFromValid->format('Y-m-d') !== $dateFrom)) {
    $errorMessages[] = 'Invalid start date supplied. Please use YYYY-MM-DD format.';
    $dateFrom = '';
}

if ($dateTo !== '' && (!$dateToValid || $dateToValid->format('Y-m-d') !== $dateTo)) {
    $errorMessages[] = 'Invalid end date supplied. Please use YYYY-MM-DD format.';
    $dateTo = '';
}

if ($dateFrom !== '' && $dateTo !== '' && $dateFrom > $dateTo) {
    $errorMessages[] = 'The start date cannot be later than the end date. Filters were not applied to dates.';
    $dateFrom = $dateTo = '';
}

if ($dateFrom !== '') {
    $whereClauses[] = 'a.appointment_date >= ?';
    $params[] = $dateFrom;
    $paramTypes[] = 's';
}

if ($dateTo !== '') {
    $whereClauses[] = 'a.appointment_date <= ?';
    $params[] = $dateTo;
    $paramTypes[] = 's';
}

$whereSql = 'WHERE ' . implode(' AND ', $whereClauses);

$totalAppointments = 0;
$totalPages = 1;

try {
    $countSql = "SELECT COUNT(*) AS total
                 FROM appointments a
                 LEFT JOIN users u ON u.id = a.student_id
                 {$whereSql}";
    $stmt = $conn->prepare($countSql);
    $stmt->bind_param(implode('', $paramTypes), ...$params);
    $stmt->execute();
    $totalAppointments = (int) ($stmt->get_result()->fetch_assoc()['total'] ?? 0);
    $stmt->close();

    $totalPages = max(1, (int) ceil($totalAppointments / $perPage));
    if ($page > $totalPages) {
        $page = $totalPages;
        $offset = ($page - 1) * $perPage;
    }
} catch (mysqli_sql_exception $e) {
    $errorMessages[] = 'Unable to fetch appointment count: ' . htmlspecialchars($e->getMessage());
}

$appointments = [];

try {
        $sql = "SELECT a.id,
               a.student_id,
               CONCAT_WS(' ', u.first_name, u.last_name) AS student_name,
               u.email AS student_email,
                   a.appointment_date,
                   a.appointment_time,
                   a.duration AS duration_minutes,
               a.status,
               a.notes,
               a.created_at
            FROM appointments a
            LEFT JOIN users u ON u.id = a.student_id
            {$whereSql}
            ORDER BY a.appointment_date DESC, a.appointment_time DESC
            LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    $bindTypes = implode('', $paramTypes) . 'ii';
    $boundParams = [...$params, $perPage, $offset];
    $stmt->bind_param($bindTypes, ...$boundParams);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    $errorMessages[] = 'Unable to fetch appointments: ' . htmlspecialchars($e->getMessage());
}

$stats = array_merge(['total' => 0], array_fill_keys(array_keys($statusDefinitions), 0));

try {
    $statsSql = "SELECT status, COUNT(*) AS cnt
                 FROM appointments
                 WHERE counselor_id = ?
                 GROUP BY status";
    $stmt = $conn->prepare($statsSql);
    $stmt->bind_param('i', $counselorId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $status = $row['status'];
        $count = (int) $row['cnt'];
        if (!array_key_exists($status, $stats)) {
            $stats[$status] = 0;
        }
        $stats[$status] = $count;
        $stats['total'] += $count;
    }
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    $errorMessages[] = 'Unable to gather statistics: ' . htmlspecialchars($e->getMessage());
}

function formatDateTime(?string $date, ?string $time): string
{
    if (!$date) {
        return '—';
    }
    try {
        $combined = trim($date . ' ' . ($time ?? '00:00:00'));
        $dt = new DateTime($combined);
        return $dt->format('M d, Y — g:i A');
    } catch (Exception) {
        return htmlspecialchars($date . ' ' . $time);
    }
}

function htmlAttr(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments Management | Virtual Career Counseling Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-bxPWQs2Kq8np5xpoE2mR7BfrpsbR9f7D3Dveoxu48UUfZo0fo0RKZ77N8BINU3CFAaj6MqFmoVoe2MktKL7Xlw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary: #1d4ed8;
            --primary-dark: #1e40af;
            --success: #15803d;
            --danger: #b91c1c;
            --warning: #b45309;
            --info: #0369a1;
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
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            padding: 2.5rem 1.5rem 2rem;
            text-align: center;
            box-shadow: 0 12px 30px -12px rgba(29, 78, 216, 0.45);
        }
        header h1 { margin: 0; font-size: 2rem; font-weight: 700; }
        header p {
            margin: 0.6rem auto 0;
            max-width: 680px;
            opacity: 0.92;
            line-height: 1.6;
        }
        main {
            max-width: 1250px;
            margin: -2.5rem auto 3rem;
            padding: 0 1.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.75rem;
        }
        .stat-card {
            background: var(--card);
            border-radius: 18px;
            padding: 1.3rem;
            box-shadow: 0 14px 35px -22px rgba(15, 23, 42, 0.35);
        }
        .stat-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.45rem;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
        }
        .filter-card, .table-card {
            background: var(--card);
            border-radius: 18px;
            padding: 1.5rem;
            box-shadow: 0 18px 40px -24px rgba(15, 23, 42, 0.35);
        }
        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }
        label { font-weight: 600; font-size: 0.92rem; color: var(--muted); }
        input[type="search"], input[type="date"], select {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.65rem 1.15rem;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--muted); }
        .btn-outline:hover { color: var(--text); }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #eef2ff; }
        th, td {
            padding: 0.85rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 0.92rem;
        }
        tbody tr:hover { background: rgba(226, 232, 240, 0.35); }
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.35rem 0.65rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .actions {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
        }
        .actions form { display: inline; }
        .actions button {
            padding: 0.45rem 0.65rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
        }
        .action-view { background: #e0f2fe; color: var(--info); }
        .action-complete { background: #dcfce7; color: var(--success); }
        .action-cancel { background: #fee2e2; color: var(--danger); }
        .table-empty {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--muted);
        }
        .pagination {
            display: flex;
            justify-content: flex-end;
            gap: 0.4rem;
            margin-top: 1.5rem;
        }
        .pagination a, .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .pagination .active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        .alert { margin-bottom: 1rem; border-radius: 12px; padding: 0.65rem 0.9rem; display: flex; gap: 0.6rem; align-items: center; }
        .alert-success { background: rgba(34, 197, 94, 0.12); color: var(--success); }
        .alert-danger { background: rgba(239, 68, 68, 0.12); color: var(--danger); }
    </style>
</head>
<body>
<header>
    <h1>Appointments Management</h1>
    <p>Review, filter, and update your counseling appointments. Stay on top of your schedule and keep student interactions organized.</p>
</header>

<main>
    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-label"><i class="fas fa-calendar-check"></i>Total</div>
            <p class="stat-value"><?= $stats['total'] ?></p>
        </article>
        <?php foreach ($statusDefinitions as $statusKey => $definition): ?>
            <article class="stat-card">
                <div class="stat-label">
                    <i class="<?= $definition['icon'] ?>" style="color: <?= $definition['statColor'] ?>;"></i>
                    <?= htmlspecialchars($definition['label']) ?>
                </div>
                <p class="stat-value" style="color: <?= $definition['statColor'] ?>;">
                    <?= $stats[$statusKey] ?? 0 ?>
                </p>
            </article>
        <?php endforeach; ?>
    </section>

    <?php if ($successMessage): ?>
        <div class="alert alert-success"><i class="fas fa-circle-check"></i><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMessages)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-circle-exclamation"></i>
            <div>
                <strong>Something went wrong:</strong>
                <ul style="margin: 0.6rem 0 0 1.1rem; padding: 0;">
                    <?php foreach ($errorMessages as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <section class="filter-card" aria-label="Appointment filters">
        <form class="filters" method="GET" action="view_appointments.php">
            <div>
                <label for="search">Search Student</label>
                <input type="search" id="search" name="search" placeholder="Search by student name" value="<?= htmlAttr($searchQuery) ?>">
            </div>
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <?php foreach ($statusOptions as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $statusFilter === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="date_from">From Date</label>
                <input type="date" id="date_from" name="date_from" value="<?= htmlAttr($dateFrom) ?>">
            </div>
            <div>
                <label for="date_to">To Date</label>
                <input type="date" id="date_to" name="date_to" value="<?= htmlAttr($dateTo) ?>">
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply Filters</button>
            </div>
            <div>
                <a href="view_appointments.php" class="btn btn-outline"><i class="fas fa-undo"></i> Reset</a>
            </div>
        </form>
    </section>

    <section class="table-card" aria-label="Appointments list">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Date &amp; Time</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($appointments)): ?>
                    <tr>
                        <td colspan="5" class="table-empty">
                            <i class="fas fa-info-circle me-2"></i>No appointments match your filters yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td>
                                <?php
                                $studentName = trim((string) ($appointment['student_name'] ?? ''));
                                if ($studentName === '') {
                                    $studentName = 'Student #' . (int) $appointment['student_id'];
                                }
                                ?>
                                <strong><?= htmlspecialchars($studentName) ?></strong><br>
                                <span class="text-muted" style="font-size: 0.82rem;">ID: <?= (int) $appointment['student_id'] ?></span>
                            </td>
                            <td><?= formatDateTime($appointment['appointment_date'], $appointment['appointment_time']) ?></td>
                            <td><?= (int) $appointment['duration_minutes'] ?> minutes</td>
                            <td>
                                <?php
                                $statusKey = (string) $appointment['status'];
                                $definition = $statusDefinitions[$statusKey] ?? null;
                                $badgeLabel = $definition['label'] ?? ucfirst(str_replace('_', ' ', $statusKey ?: 'Unknown'));
                                $badgeBg = $definition['badgeBg'] ?? '#e2e8f0';
                                $badgeColor = $definition['badgeColor'] ?? '#475569';
                                $canMarkComplete = in_array($statusKey, ['scheduled', 'confirmed', 'pending'], true);
                                $canCancel = in_array($statusKey, ['scheduled', 'confirmed', 'pending'], true);
                                ?>
                                <span class="badge" style="background: <?= $badgeBg ?>; color: <?= $badgeColor ?>;">
                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                    <?= htmlspecialchars($badgeLabel) ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="action-view" href="appointment_details.php?id=<?= (int) $appointment['id'] ?>" title="View details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($canMarkComplete): ?>
                                        <form method="POST" action="update_appointment_status.php" class="status-form" data-title="Mark as completed?">
                                            <input type="hidden" name="appointment_id" value="<?= (int) $appointment['id'] ?>">
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="action-complete" title="Mark as completed">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($canCancel): ?>
                                        <form method="POST" action="update_appointment_status.php" class="status-form" data-title="Cancel this appointment?">
                                            <input type="hidden" name="appointment_id" value="<?= (int) $appointment['id'] ?>">
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="action-cancel" title="Cancel appointment">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="pagination" aria-label="Pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php
                    $query = $_GET;
                    $query['page'] = $i;
                    $url = 'view_appointments.php?' . http_build_query($query);
                    ?>
                    <?php if ($i === $page): ?>
                        <span class="active"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= $url ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>
    </section>
</main>

<script>
(function () {
    const forms = document.querySelectorAll('.status-form');
    forms.forEach(form => {
        form.addEventListener('submit', function (event) {
            const message = form.dataset.title || 'Are you sure?';
            if (!confirm(message)) {
                event.preventDefault();
            }
        });
    });
})();
</script>
</body>
</html>
