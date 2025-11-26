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

$errors = $_SESSION['schedule_errors'] ?? [];
$successMessage = $_SESSION['schedule_success'] ?? '';
$oldInput = $_SESSION['schedule_old'] ?? [];

unset($_SESSION['schedule_errors'], $_SESSION['schedule_success'], $_SESSION['schedule_old']);

$scheduleRows = [];

try {
    $stmt = $conn->prepare(
        'SELECT id, date, start_time, end_time, duration_minutes, is_available, created_at
         FROM counselor_schedule
         WHERE counselor_id = ?
         ORDER BY date ASC, start_time ASC'
    );
    $stmt->bind_param('i', $counselorId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $scheduleRows[] = $row;
    }
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    $errors[] = 'Failed to load schedule: ' . htmlspecialchars($e->getMessage());
}

$today = (new DateTime('today'))->format('Y-m-d');
$defaultStart = (new DateTime('+1 hour'))->format('H:00');
$defaultEnd = (new DateTime('+2 hour'))->format('H:00');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Schedule | Virtual Career Counseling Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-bxPWQs2Kq8np5xpoE2mR7BfrpsbR9f7D3Dveoxu48UUfZo0fo0RKZ77N8BINU3CFAaj6MqFmoVoe2MktKL7Xlw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary: #1d4ed8;
            --primary-dark: #1e40af;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #f59e0b;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #0f172a;
        }
        * {
            box-sizing: border-box;
        }
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
        header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }
        header p {
            margin: 0.75rem auto 0;
            max-width: 640px;
            opacity: 0.9;
            line-height: 1.5;
        }
        main {
            max-width: 1100px;
            margin: -2.5rem auto 3rem;
            padding: 0 1.5rem;
        }
        .grid {
            display: grid;
            gap: 1.5rem;
        }
        @media (min-width: 992px) {
            .grid {
                grid-template-columns: 360px 1fr;
            }
        }
        .card {
            background: var(--card);
            border-radius: 18px;
            padding: 1.75rem;
            box-shadow: 0 18px 40px -24px rgba(15, 23, 42, 0.35);
        }
        .card h2 {
            margin: 0 0 1rem;
            font-size: 1.35rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .card h2 i {
            color: var(--primary);
            font-size: 1.25rem;
        }
        .alert {
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
        }
        .alert-success {
            background: rgba(22, 163, 74, 0.12);
            color: var(--success);
        }
        .alert-danger {
            background: rgba(220, 38, 38, 0.12);
            color: var(--danger);
        }
        .form-group {
            margin-bottom: 1.1rem;
        }
        label {
            display: block;
            margin-bottom: 0.45rem;
            font-weight: 600;
            font-size: 0.95rem;
        }
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border-radius: 12px;
            border: 1px solid #cbd5f5;
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        input:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.7rem 1.2rem;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.95rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 10px 22px -12px rgba(29, 78, 216, 0.55);
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #0f172a;
        }
        .btn-danger {
            background: rgba(220, 38, 38, 0.1);
            color: var(--danger);
            border: 1px solid rgba(220, 38, 38, 0.35);
        }
        .btn-danger:hover {
            background: rgba(220, 38, 38, 0.18);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.93rem;
        }
        thead {
            background: #eef2ff;
        }
        th, td {
            padding: 0.9rem 0.65rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        tbody tr:hover {
            background: rgba(226, 232, 240, 0.35);
        }
        .table-empty {
            text-align: center;
            padding: 2rem 1.5rem;
            color: #64748b;
        }
        .badge {
            display: inline-block;
            padding: 0.35rem 0.6rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .badge-available {
            background: rgba(22, 163, 74, 0.12);
            color: var(--success);
        }
        .badge-unavailable {
            background: rgba(220, 38, 38, 0.12);
            color: var(--danger);
        }
        .table-actions {
            display: flex;
            gap: 0.4rem;
        }
        .meta {
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #475569;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 1.25rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            color: var(--primary-dark);
        }
    </style>
</head>
<body>
<header>
    <h1>Manage Your Schedule</h1>
    <p>Define your availability so students can book appointments at times that work for both of you.</p>
</header>

<main>
    <div class="grid">
        <section class="card">
            <h2><i class="fas fa-plus-circle"></i>Add Time Slot</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-circle-exclamation"></i>
                    <div>
                        <strong>Please fix the following issues:</strong>
                        <ul style="margin: 0.65rem 0 0 1.1rem; padding: 0;">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($successMessage): ?>
                <div class="alert alert-success">
                    <i class="fas fa-circle-check"></i>
                    <span><?= htmlspecialchars($successMessage) ?></span>
                </div>
            <?php endif; ?>

            <form id="slotForm" action="add_slot.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" min="<?= $today ?>" required value="<?= htmlspecialchars($oldInput['date'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="time" id="start_time" name="start_time" required value="<?= htmlspecialchars($oldInput['start_time'] ?? $defaultStart) ?>">
                </div>

                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="time" id="end_time" name="end_time" required value="<?= htmlspecialchars($oldInput['end_time'] ?? $defaultEnd) ?>">
                </div>

                <div class="form-group">
                    <label for="duration">Duration (minutes)</label>
                    <select id="duration" name="duration" required>
                        <?php
                        $durations = [30, 45, 60];
                        $selectedDuration = (int) ($oldInput['duration'] ?? 30);
                        foreach ($durations as $duration) {
                            $selected = $selectedDuration === $duration ? 'selected' : '';
                            echo '<option value="' . $duration . '" ' . $selected . '>' . $duration . ' minutes</option>';
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Time Slot
                </button>
            </form>

            <p class="meta">
                <i class="fas fa-lightbulb me-2"></i>
                Time slots must be at least 30 minutes, use future dates only, and cannot overlap with existing availability.
            </p>

            <a class="back-link" href="<?= APP_URL ?>/counselor/dashboard">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </section>

        <section class="card">
            <h2><i class="fas fa-calendar-week"></i>Upcoming Availability</h2>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($scheduleRows)): ?>
                        <tr>
                            <td class="table-empty" colspan="6">
                                <i class="fas fa-info-circle"></i>
                                No availability defined yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($scheduleRows as $slot): ?>
                            <tr>
                                <td><?= htmlspecialchars((new DateTime($slot['date']))->format('M d, Y')) ?></td>
                                <td><?= htmlspecialchars((new DateTime($slot['start_time']))->format('g:i A')) ?></td>
                                <td><?= htmlspecialchars((new DateTime($slot['end_time']))->format('g:i A')) ?></td>
                                <td><?= (int) $slot['duration_minutes'] ?> mins</td>
                                <td>
                                    <span class="badge <?= $slot['is_available'] ? 'badge-available' : 'badge-unavailable' ?>">
                                        <?= $slot['is_available'] ? 'Available' : 'Unavailable' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <form action="delete_slot.php" method="POST" onsubmit="return confirm('Delete this time slot?');">
                                            <input type="hidden" name="slot_id" value="<?= (int) $slot['id'] ?>">
                                            <button type="submit" class="btn btn-danger" title="Delete slot">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<script>
(function () {
    const form = document.getElementById('slotForm');
    if (!form) return;

    const dateInput = document.getElementById('date');
    const startInput = document.getElementById('start_time');
    const endInput = document.getElementById('end_time');
    const durationSelect = document.getElementById('duration');

    dateInput.addEventListener('change', () => {
        const selectedDate = new Date(dateInput.value + 'T00:00:00');
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
            alert('Please choose a future date for your availability.');
            dateInput.value = '';
        }
    });

    form.addEventListener('submit', (event) => {
        if (!startInput.value || !endInput.value) {
            return;
        }

        const start = startInput.value;
        const end = endInput.value;
        if (end <= start) {
            event.preventDefault();
            alert('End time must be later than start time.');
            return;
        }

        const startDate = new Date('1970-01-01T' + start + ':00');
        const endDate = new Date('1970-01-01T' + end + ':00');
        const diffMinutes = (endDate - startDate) / (1000 * 60);
        const duration = parseInt(durationSelect.value, 10);

        if (diffMinutes < 30) {
            event.preventDefault();
            alert('Time slots must be at least 30 minutes long.');
            return;
        }

        if (diffMinutes < duration) {
            event.preventDefault();
            alert('Selected duration exceeds the total time range.');
        }
    });
})();
</script>
</body>
</html>
