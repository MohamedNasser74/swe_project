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

$counselorId = (int) $_SESSION['counselor_id'];

$studentId = isset($_GET['id']) ? (int) $_GET['id'] : (isset($_POST['id']) ? (int) $_POST['id'] : 0);
if ($studentId <= 0) {
    $_SESSION['students_errors'] = ['Student not found.'];
    header('Location: my_students.php');
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    $_SESSION['students_errors'] = ['Database connection failed: ' . $e->getMessage()];
    header('Location: my_students.php');
    exit;
}

function fetchStudent(mysqli $conn, int $studentId, int $counselorId): ?array
{
    $sql = "SELECT * FROM students WHERE id = ? AND counselor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $studentId, $counselorId);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $student ?: null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student = fetchStudent($conn, $studentId, $counselorId);
    if (!$student) {
        $_SESSION['students_errors'] = ['Student not found or access denied.'];
        header('Location: my_students.php');
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $education = trim($_POST['education_level'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $status = trim($_POST['status'] ?? 'active');
    $notes = trim($_POST['notes'] ?? '');
    $lastSessionDate = trim($_POST['last_session_date'] ?? '');
    $totalSessions = (int) ($_POST['total_sessions'] ?? $student['total_sessions']);

    $errors = [];

    if ($name === '' || strlen($name) < 2) {
        $errors[] = 'Student name must be at least 2 characters.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please provide a valid email address.';
    }

    $allowedStatus = ['active', 'inactive', 'completed'];
    if (!in_array($status, $allowedStatus, true)) {
        $status = 'active';
    }

    if ($lastSessionDate !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $lastSessionDate);
        if (!$d || $d->format('Y-m-d') !== $lastSessionDate) {
            $errors[] = 'Last session date must use YYYY-MM-DD format.';
        }
    } else {
        $lastSessionDate = null;
    }

    if (!empty($errors)) {
        $_SESSION['students_errors'] = $errors;
        header('Location: student_profile.php?id=' . $studentId);
        exit;
    }

    try {
        $sql = "UPDATE students
                SET name = ?, email = ?, phone = ?, education_level = ?, specialization = ?, status = ?, notes = ?, last_session_date = ?, total_sessions = ?
                WHERE id = ? AND counselor_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'ssssssssiii',
            $name,
            $email,
            $phone,
            $education,
            $specialization,
            $status,
            $notes,
            $lastSessionDate,
            $totalSessions,
            $studentId,
            $counselorId
        );
        $stmt->execute();
        $stmt->close();

        $_SESSION['students_success'] = 'Student details updated successfully.';
        header('Location: student_profile.php?id=' . $studentId);
        exit;
    } catch (mysqli_sql_exception $e) {
        $_SESSION['students_errors'] = ['Unable to update student: ' . $e->getMessage()];
        header('Location: student_profile.php?id=' . $studentId);
        exit;
    }
}

$student = fetchStudent($conn, $studentId, $counselorId);
if (!$student) {
    $_SESSION['students_errors'] = ['Student not found or access denied.'];
    header('Location: my_students.php');
    exit;
}

function safe(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student | <?= safe($student['name']) ?></title>
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
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: #0f172a;
        }
        header {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.92), rgba(29, 78, 216, 0.95));
            color: #fff;
            padding: 2.2rem 1.5rem 1.8rem;
        }
        header a { color: #fff; text-decoration: none; font-weight: 600; }
        main {
            max-width: 860px;
            margin: -2rem auto 3rem;
            padding: 0 1.5rem;
        }
        .card {
            background: var(--card);
            border-radius: 18px;
            box-shadow: 0 22px 44px -30px rgba(15,23,42,0.35);
            padding: 2rem;
        }
        .form-grid {
            display: grid;
            gap: 1rem;
        }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
        label {
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--secondary);
        }
        input, select, textarea {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 0.95rem;
        }
        textarea { min-height: 120px; resize: vertical; }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        .btn {
            border: none;
            border-radius: 12px;
            padding: 0.65rem 1.15rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-muted { background: rgba(148,163,184,0.2); color: var(--secondary); }
    </style>
</head>
<body>
<header>
    <a href="student_profile.php?id=<?= $studentId ?>"><i class="fas fa-arrow-left"></i> Back to Profile</a>
    <h1 style="margin-top: 1rem;">Edit Student</h1>
</header>

<main>
    <section class="card">
        <form method="POST" class="form-grid" action="update_student.php" onsubmit="return validateForm();" id="updateStudentForm">
            <input type="hidden" name="id" value="<?= $studentId ?>">
            <div class="form-grid grid-2">
                <div>
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" value="<?= safe($student['name']) ?>" required>
                </div>
                <div>
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?= safe($student['email']) ?>" required>
                </div>
                <div>
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?= safe($student['phone']) ?>">
                </div>
                <div>
                    <label for="education">Education Level</label>
                    <input type="text" id="education" name="education_level" value="<?= safe($student['education_level']) ?>">
                </div>
                <div>
                    <label for="specialization">Specialization</label>
                    <input type="text" id="specialization" name="specialization" value="<?= safe($student['specialization']) ?>">
                </div>
                <div>
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="active" <?= ($student['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($student['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        <option value="completed" <?= ($student['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>
                <div>
                    <label for="last_session_date">Last Session Date</label>
                    <input type="date" id="last_session_date" name="last_session_date" value="<?= safe($student['last_session_date']) ?>">
                </div>
                <div>
                    <label for="total_sessions">Total Sessions</label>
                    <input type="number" id="total_sessions" name="total_sessions" min="0" value="<?= (int) ($student['total_sessions'] ?? 0) ?>">
                </div>
            </div>
            <div>
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" placeholder="Counselor notes, goals, and progress details"><?= safe($student['notes']) ?></textarea>
            </div>
            <div class="actions">
                <a href="student_profile.php?id=<?= $studentId ?>" class="btn btn-muted"><i class="fas fa-times"></i>Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Save Changes</button>
            </div>
        </form>
    </section>
</main>

<script>
function validateForm() {
    const form = document.getElementById('updateStudentForm');
    const name = form.name.value.trim();
    const email = form.email.value.trim();
    if (name.length < 2) {
        alert('Student name must be at least 2 characters.');
        return false;
    }
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Please provide a valid email address.');
        return false;
    }
    return true;
}
</script>
</body>
</html>
