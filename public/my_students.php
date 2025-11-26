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

$successMessage = $_SESSION['students_success'] ?? '';
$errorMessages = $_SESSION['students_errors'] ?? [];
unset($_SESSION['students_success'], $_SESSION['students_errors']);

$dataUnavailable = false;

$statusOptions = [
    'all' => 'All Statuses',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'completed' => 'Completed'
];
$statusFilter = strtolower(trim($_GET['status'] ?? 'all'));
$statusFilter = array_key_exists($statusFilter, $statusOptions) ? $statusFilter : 'all';

$educationFilter = trim($_GET['education'] ?? 'all');
$searchQuery = trim($_GET['search'] ?? '');

$educationOptions = ['all' => 'All Education Levels'];

try {
    $sql = "SELECT DISTINCT COALESCE(education_level, '') AS level
            FROM students
            WHERE counselor_id = ? AND COALESCE(education_level, '') <> ''
            ORDER BY level";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $counselorId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $educationOptions[$row['level']] = $row['level'];
    }
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    if ((int) $e->getCode() === 1146) {
        $dataUnavailable = true;
    } else {
        $errorMessages[] = 'Unable to load education list: ' . htmlspecialchars($e->getMessage());
    }
}

$filters = ['counselor_id = ?'];
$params = [$counselorId];
$types = 'i';

if ($statusFilter !== 'all') {
    $filters[] = 'status = ?';
    $params[] = $statusFilter;
    $types .= 's';
}

if ($educationFilter !== 'all' && isset($educationOptions[$educationFilter])) {
    $filters[] = "COALESCE(education_level, '') = ?";
    $params[] = $educationFilter;
    $types .= 's';
}

if ($searchQuery !== '') {
    $filters[] = '(name LIKE ? OR email LIKE ? OR phone LIKE ?)';
    $like = '%' . $searchQuery . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= 'sss';
}

$whereClause = 'WHERE ' . implode(' AND ', $filters);

$students = [];

try {
    $sql = "SELECT id, name, email, phone, education_level, specialization, status, join_date, last_session_date, total_sessions
            FROM students
            {$whereClause}
            ORDER BY name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    if ((int) $e->getCode() === 1146) {
        $dataUnavailable = true;
        $students = [];
    } else {
        $errorMessages[] = 'Unable to fetch students: ' . htmlspecialchars($e->getMessage());
    }
}

$stats = [
    'total' => 0,
    'active' => 0,
    'completed' => 0,
    'new_month' => 0
];

try {
    $statsSql = "SELECT
                    COUNT(*) AS total_students,
                    SUM(status = 'active') AS active_students,
                    SUM(status = 'completed') AS completed_students,
                    SUM(join_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')) AS new_this_month
                 FROM students
                 WHERE counselor_id = ?";
    $stmt = $conn->prepare($statsSql);
    $stmt->bind_param('i', $counselorId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        $stats['total'] = (int) ($row['total_students'] ?? 0);
        $stats['active'] = (int) ($row['active_students'] ?? 0);
        $stats['completed'] = (int) ($row['completed_students'] ?? 0);
        $stats['new_month'] = (int) ($row['new_this_month'] ?? 0);
    }
} catch (mysqli_sql_exception $e) {
    if ((int) $e->getCode() === 1146) {
        $dataUnavailable = true;
    } else {
        $errorMessages[] = 'Unable to load statistics: ' . htmlspecialchars($e->getMessage());
    }
}

function safe(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDateValue(?string $date): string
{
    if (!$date) {
        return '—';
    }
    try {
        return (new DateTime($date))->format('M d, Y');
    } catch (Exception) {
        return safe($date);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students | Virtual Career Counseling Platform</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f6f8;
            color: #1f2933;
        }
        .wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1.25rem 3rem;
        }
        header {
            margin-bottom: 2rem;
        }
        header h1 {
            margin: 0 0 0.5rem;
            font-size: 2rem;
        }
        header p {
            margin: 0;
            color: #52616b;
        }
        .top-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }
        button, .btn-link {
            padding: 0.55rem 0.9rem;
            border-radius: 8px;
            border: 1px solid #2563eb;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
        }
        .btn-secondary {
            background: transparent;
            color: #2563eb;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .card {
            background: #fff;
            border: 1px solid #e3e8ee;
            border-radius: 10px;
            padding: 1rem;
        }
        .card span {
            color: #52616b;
            font-size: 0.9rem;
        }
        .card strong {
            display: block;
            margin-top: 0.35rem;
            font-size: 1.4rem;
        }
        .filters {
            background: #fff;
            border: 1px solid #e3e8ee;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
        }
        label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
        input[type="search"], select {
            width: 100%;
            padding: 0.55rem;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
        }
        .list {
            background: #fff;
            border: 1px solid #e3e8ee;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #eef2f6;
        }
        th {
            background: #f8fafc;
            font-size: 0.9rem;
        }
        .empty {
            text-align: center;
            padding: 2.5rem 1rem;
            color: #52616b;
        }
        .alerts {
            margin-bottom: 1.5rem;
            display: grid;
            gap: 0.65rem;
        }
        .alert {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-weight: 600;
        }
        .alert-success {
            background: #ecfdf3;
            color: #0b8a4a;
            border: 1px solid #9ae6b4;
        }
        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .add-panel {
            display: none;
            margin-top: 1rem;
            background: #fff;
            border: 1px solid #e3e8ee;
            border-radius: 10px;
            padding: 1rem;
        }
        .add-panel.active {
            display: block;
        }
        .add-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.75rem;
        }
        textarea {
            width: 100%;
            min-height: 100px;
            padding: 0.55rem;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
        }
        @media (max-width: 640px) {
            button, .btn-link {
                width: 100%;
                text-align: center;
            }
            th, td {
                display: block;
            }
            td {
                border-bottom: none;
                padding: 0.5rem 0;
            }
            tr {
                border-bottom: 1px solid #eef2f6;
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <header>
        <h1>My Students</h1>
        <p>Keep a quick snapshot of every student you support and update details when needed.</p>
        <div class="top-actions">
            <button type="button" id="toggleAdd">Add Student</button>
            <a class="btn-link btn-secondary" href="<?= APP_URL ?>/counselor/dashboard">Back to Dashboard</a>
        </div>
    </header>

    <?php if ($successMessage || !empty($errorMessages)): ?>
        <div class="alerts">
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?= safe($successMessage) ?></div>
            <?php endif; ?>
            <?php foreach ($errorMessages as $error): ?>
                <div class="alert alert-error"><?= safe($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($dataUnavailable && empty($errorMessages)): ?>
        <div class="alerts">
            <div class="alert" style="background: #eef2f6; color: #52616b; border: 1px solid #d8dee6;">
                Student records will show here once the data source is ready.
            </div>
        </div>
    <?php endif; ?>

    <section class="cards" aria-label="Student statistics">
        <article class="card">
            <span>Total Students</span>
            <strong><?= $stats['total'] ?></strong>
            <small><?= $stats['new_month'] ?> new this month</small>
        </article>
        <article class="card">
            <span>Active Students</span>
            <strong><?= $stats['active'] ?></strong>
        </article>
        <article class="card">
            <span>Completed Programs</span>
            <strong><?= $stats['completed'] ?></strong>
        </article>
    </section>

    <section class="add-panel" id="addPanel" aria-label="Add student">
        <form action="add_student.php" method="POST">
            <div class="add-grid">
                <div>
                    <label for="addName">Full Name *</label>
                    <input type="text" id="addName" name="name" required>
                </div>
                <div>
                    <label for="addEmail">Email *</label>
                    <input type="email" id="addEmail" name="email" required>
                </div>
                <div>
                    <label for="addPhone">Phone</label>
                    <input type="tel" id="addPhone" name="phone">
                </div>
                <div>
                    <label for="addEducation">Education Level</label>
                    <input type="text" id="addEducation" name="education_level">
                </div>
                <div>
                    <label for="addSpecialization">Specialization</label>
                    <input type="text" id="addSpecialization" name="specialization">
                </div>
                <div>
                    <label for="addStatus">Status</label>
                    <select id="addStatus" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 0.75rem;">
                <label for="addNotes">Notes</label>
                <textarea id="addNotes" name="notes"></textarea>
            </div>
            <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem;">
                <button type="submit">Save Student</button>
                <button type="button" class="btn-secondary" id="cancelAdd">Cancel</button>
            </div>
        </form>
    </section>

    <form class="filters" method="GET" action="my_students.php" id="filtersForm">
        <div>
            <label for="search">Search students</label>
            <input type="search" id="search" name="search" value="<?= safe($searchQuery) ?>" placeholder="Name, email, or phone">
        </div>
        <div>
            <label for="status">Status</label>
            <select id="status" name="status">
                <?php foreach ($statusOptions as $value => $label): ?>
                    <option value="<?= safe($value) ?>" <?= $statusFilter === $value ? 'selected' : '' ?>><?= safe($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="education">Education Level</label>
            <select id="education" name="education">
                <?php foreach ($educationOptions as $value => $label): ?>
                    <option value="<?= safe($value) ?>" <?= $educationFilter === $value ? 'selected' : '' ?>><?= safe($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
            <button type="submit">Apply</button>
            <a class="btn-link btn-secondary" href="my_students.php">Reset</a>
        </div>
    </form>

    <section class="list" aria-label="Student list">
        <?php if (!empty($students)): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Last Session</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td>
                                    <strong><?= safe($student['name']) ?></strong><br>
                                    <small><?= safe($student['education_level'] ?: 'Education not set') ?></small>
                                </td>
                                <td>
                                    <div><?= safe($student['email'] ?: 'No email') ?></div>
                                    <div><?= safe($student['phone'] ?: 'No phone') ?></div>
                                </td>
                                <td><?= safe(ucfirst($student['status'] ?? '')) ?></td>
                                <td><?= formatDateValue($student['last_session_date'] ?? null) ?></td>
                                <td>
                                    <a class="btn-link btn-secondary" href="student_profile.php?id=<?= (int) $student['id'] ?>">Profile</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty">
                <p>No students found yet.</p>
                <p>Use the "Add Student" button above when you are ready to create your first record.</p>
            </div>
        <?php endif; ?>
    </section>
</div>

<script>
(function() {
    const addPanel = document.getElementById('addPanel');
    const toggleAdd = document.getElementById('toggleAdd');
    const cancelAdd = document.getElementById('cancelAdd');

    toggleAdd.addEventListener('click', function () {
        addPanel.classList.toggle('active');
        toggleAdd.textContent = addPanel.classList.contains('active') ? 'Hide Form' : 'Add Student';
    });

    cancelAdd.addEventListener('click', function () {
        addPanel.classList.remove('active');
        toggleAdd.textContent = 'Add Student';
    });
})();
</script>
</body>
</html>
