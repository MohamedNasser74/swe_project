<?php ob_start(); ?>

<?php
$totalStudents = count($students ?? []);
$activeToday = 0;
$recentCutoff = (new DateTime('now'))->modify('-30 days');
$recentCount = 0;
$completedCount = 0;

foreach ($students ?? [] as $student) {
    $last = isset($student->last_appointment) ? DateTime::createFromFormat('Y-m-d', $student->last_appointment) : null;
    if ($last && $last >= $recentCutoff) {
        $recentCount++;
    }
    if (!empty($student->last_appointment) && $student->last_appointment === date('Y-m-d')) {
        $activeToday++;
    }
    if (!empty($student->total_appointments) && $student->total_appointments >= 5) {
        $completedCount++;
    }
}

$completionRate = $totalStudents > 0 ? round(($completedCount / $totalStudents) * 100) : 0;

function counselorStudentName($student)
{
    $first = $student->first_name ?? '';
    $last = $student->last_name ?? '';
    $full = trim($first . ' ' . $last);
    return $full !== '' ? $full : ($student->username ?? 'Student #' . $student->id);
}

function counselorStudentEmail($student)
{
    return htmlspecialchars($student->email ?? '');
}

function counselorStudentPhone($student)
{
    return htmlspecialchars($student->phone ?? 'Not provided');
}

function counselorStudentDate($date)
{
    if (!$date) {
        return '—';
    }
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    return $dt ? $dt->format('M d, Y') : htmlspecialchars($date);
}
?>

<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-1">My Students</h1>
            <p class="text-muted mb-0">Track student progress, touchpoints, and session cadence in one place.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="<?= APP_URL ?>/counselor/dashboard" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted text-uppercase small">Total Students</span>
                        <i class="fas fa-users text-primary"></i>
                    </div>
                    <h3 class="mt-2 mb-0"><?= $totalStudents ?></h3>
                    <small class="text-muted"><?= $recentCount ?> engaged last 30 days</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted text-uppercase small">Sessions Today</span>
                        <i class="fas fa-calendar-day text-success"></i>
                    </div>
                    <h3 class="mt-2 mb-0"><?= $activeToday ?></h3>
                    <small class="text-muted">Students with appointments today</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted text-uppercase small">High Engagement</span>
                        <i class="fas fa-chart-line text-info"></i>
                    </div>
                    <h3 class="mt-2 mb-0"><?= $completedCount ?></h3>
                    <small class="text-muted">5+ sessions with you</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted text-uppercase small">Completion Rate</span>
                        <i class="fas fa-bullseye text-warning"></i>
                    </div>
                    <h3 class="mt-2 mb-0"><?= $completionRate ?>%</h3>
                    <small class="text-muted">Students across milestone threshold</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="studentSearch" class="form-label text-muted">Search students</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="search" id="studentSearch" class="form-control" placeholder="Search by name, email, or phone">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="activityFilter" class="form-label text-muted">Activity</label>
                    <select id="activityFilter" class="form-select">
                        <option value="all" selected>All students</option>
                        <option value="recent">Active last 30 days</option>
                        <option value="inactive">No recent sessions</option>
                        <option value="engaged">5+ sessions</option>
                    </select>
                </div>
                <div class="col-md-3 text-md-end">
                    <a href="<?= APP_URL ?>/my_students.php" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Open Student Hub
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Student Directory</h5>
            <span class="badge bg-secondary">Total: <?= $totalStudents ?></span>
        </div>
        <div class="card-body p-0">
            <?php if ($totalStudents > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="studentsTable">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Student</th>
                                <th scope="col">Contact</th>
                                <th scope="col">Appointments</th>
                                <th scope="col">Last Session</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <?php
                                $fullName = counselorStudentName($student);
                                $email = counselorStudentEmail($student);
                                $phone = counselorStudentPhone($student);
                                $total = (int) ($student->total_appointments ?? 0);
                                $last = counselorStudentDate($student->last_appointment ?? null);
                                $activityTag = 'inactive';
                                $lastDate = isset($student->last_appointment) ? DateTime::createFromFormat('Y-m-d', $student->last_appointment) : null;
                                if ($lastDate && $lastDate >= $recentCutoff) {
                                    $activityTag = 'recent';
                                }
                                if ($total >= 5) {
                                    $activityTag = 'engaged';
                                }
                                ?>
                                <tr data-name="<?= htmlspecialchars(strtolower($fullName . ' ' . ($student->email ?? '') . ' ' . ($student->phone ?? ''))) ?>" data-activity="<?= $activityTag ?>">
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($fullName) ?></div>
                                        <div class="small text-muted">Joined: <?= counselorStudentDate($student->created_at ?? null) ?></div>
                                    </td>
                                    <td>
                                        <div><i class="fas fa-envelope me-2 text-muted"></i><?= $email ?></div>
                                        <div><i class="fas fa-phone me-2 text-muted"></i><?= $phone ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $total >= 5 ? 'success' : 'primary' ?> bg-opacity-10 text-<?= $total >= 5 ? 'success' : 'primary' ?>">
                                            <?= $total ?> sessions
                                        </span>
                                    </td>
                                    <td><?= $last ?></td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="<?= APP_URL ?>/student_profile.php?id=<?= (int) $student->id ?>" class="btn btn-sm btn-outline-primary" title="View profile" target="_blank">
                                                <i class="fas fa-id-badge"></i>
                                            </a>
                                            <a href="mailto:<?= $email ?>" class="btn btn-sm btn-outline-secondary" title="Email student">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                            <a href="<?= APP_URL ?>/student_sessions.php?id=<?= (int) $student->id ?>" class="btn btn-sm btn-outline-info" title="Session history" target="_blank">
                                                <i class="fas fa-calendar"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No students yet</h6>
                    <p class="text-muted">Once you schedule sessions, enrolled students will appear here.</p>
                    <a href="<?= APP_URL ?>/my_students.php" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Add your first student</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
(function() {
    const searchField = document.getElementById('studentSearch');
    const activityField = document.getElementById('activityFilter');
    const rows = Array.from(document.querySelectorAll('#studentsTable tbody tr'));

    function applyFilters() {
        const searchTerm = searchField.value.trim().toLowerCase();
        const activity = activityField.value;
        rows.forEach(row => {
            const matchesSearch = row.dataset.name.includes(searchTerm);
            const matchesActivity = activity === 'all' || row.dataset.activity === activity;
            row.style.display = matchesSearch && matchesActivity ? '' : 'none';
        });
    }

    if (rows.length) {
        searchField.addEventListener('input', applyFilters);
        activityField.addEventListener('change', applyFilters);
    }
})();
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
