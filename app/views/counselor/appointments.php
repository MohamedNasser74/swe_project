<?php ob_start(); ?>

<?php
$statusDefinitions = [
    'scheduled' => ['label' => 'Scheduled', 'badge' => 'info'],
    'confirmed' => ['label' => 'Confirmed', 'badge' => 'primary'],
    'pending' => ['label' => 'Pending', 'badge' => 'warning'],
    'completed' => ['label' => 'Completed', 'badge' => 'success'],
    'cancelled' => ['label' => 'Cancelled', 'badge' => 'danger'],
    'no_show' => ['label' => 'No-Show', 'badge' => 'dark'],
];

$filterOptions = ['all' => 'All Appointments'];
foreach ($statusDefinitions as $key => $definition) {
    $filterOptions[$key] = $definition['label'];
}

$currentStatus = strtolower($current_status ?? 'all');
$currentStatus = array_key_exists($currentStatus, $filterOptions) ? $currentStatus : 'all';

function convertToBadgeClass(string $status, array $definitions): string
{
    return $definitions[$status]['badge'] ?? 'secondary';
}

function formatDateTimeString(?string $date, ?string $time): string
{
    if (!$date) {
        return '—';
    }
    $timestamp = strtotime(trim($date . ' ' . ($time ?? '00:00:00')));
    return $timestamp ? date('M d, Y \a\t g:i A', $timestamp) : htmlspecialchars(trim(($date ?? '') . ' ' . ($time ?? '')));
}

function sanitize(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-1">My Appointments</h1>
            <p class="text-muted mb-0">Review upcoming sessions, track outcomes, and respond to requests.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="<?= APP_URL ?>/counselor/dashboard" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form class="row gy-2 gx-3 align-items-end" method="GET" action="<?= APP_URL ?>/counselor/appointments">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <?php foreach ($filterOptions as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $currentStatus === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Apply Filter
                    </button>
                </div>
                <div class="col-12 col-md-auto">
                    <a href="<?= APP_URL ?>/counselor/appointments" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <?= CalendarHelper::getStyles() ?>

    <!-- View Toggle Tabs -->
    <ul class="nav nav-tabs mb-4" id="viewTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendarView" 
                    type="button" role="tab">
                <i class="fas fa-calendar-alt me-2"></i>Calendar View
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#listView" 
                    type="button" role="tab">
                <i class="fas fa-list me-2"></i>List View
            </button>
        </li>
    </ul>

    <div class="tab-content" id="viewTabsContent">
        <!-- Calendar View -->
        <div class="tab-pane fade show active" id="calendarView" role="tabpanel">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <?php
                    $month = $_GET['month'] ?? date('m');
                    $year = $_GET['year'] ?? date('Y');
                    $calendar = new CalendarHelper($year, $month);
                    $calendar->setAppointments($appointments ?? []);
                    echo $calendar->render(APP_URL . '/counselor/appointments?status=' . $currentStatus);
                    ?>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div class="tab-pane fade" id="listView" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2 text-primary"></i>Appointments</h5>
                    <span class="badge bg-secondary">Total: <?= count($appointments ?? []) ?></span>
                </div>
                <div class="card-body p-0">
            <?php if (!empty($appointments)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Student</th>
                                <th scope="col">Date &amp; Time</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Type</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment): ?>
                                <?php
                                $statusKey = strtolower($appointment->status ?? '');
                                $badgeClass = 'bg-' . convertToBadgeClass($statusKey, $statusDefinitions);
                                $statusLabel = $filterOptions[$statusKey] ?? ucfirst($statusKey ?: 'Unknown');
                                $durationMinutes = isset($appointment->duration_minutes) ? (int) $appointment->duration_minutes : (int) ($appointment->duration ?? 0);
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= sanitize($appointment->student_name ?? 'Student #' . ($appointment->student_id ?? '')) ?></div>
                                        <?php if (!empty($appointment->student_email)): ?>
                                            <div class="small text-muted">
                                                <i class="fas fa-envelope me-1"></i><?= sanitize($appointment->student_email) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= formatDateTimeString($appointment->appointment_date, $appointment->appointment_time) ?></td>
                                    <td><?= $durationMinutes ?> min</td>
                                    <td><?= sanitize(ucwords(str_replace('_', ' ', $appointment->session_type ?? ''))) ?></td>
                                    <td>
                                        <span class="badge <?= $badgeClass ?> bg-opacity-25 text-<?= convertToBadgeClass($statusKey, $statusDefinitions) ?>">
                                            <?= sanitize($statusLabel) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="<?= APP_URL ?>/appointment_details.php?id=<?= (int) $appointment->id ?>" class="btn btn-sm btn-outline-primary" title="View details" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if (in_array($statusKey, ['scheduled', 'confirmed', 'pending'], true)): ?>
                                                <form method="POST" action="<?= APP_URL ?>/counselor/updateAppointmentStatus" class="d-inline">
                                                    <input type="hidden" name="appointment_id" value="<?= (int) $appointment->id ?>">
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Confirm appointment" onclick="return confirm('Mark this appointment as confirmed?');">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <?php if (!in_array($statusKey, ['cancelled', 'completed', 'no_show'], true)): ?>
                                                <form method="POST" action="<?= APP_URL ?>/counselor/updateAppointmentStatus" class="d-inline">
                                                    <input type="hidden" name="appointment_id" value="<?= (int) $appointment->id ?>">
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel appointment" onclick="return confirm('Cancel this appointment?');">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No appointments found for the selected filter.</h6>
                    <p class="text-muted">Try choosing a different status or check back later.</p>
                </div>
            <?php endif; ?>
                </div>
            </div>
        </div><!-- End List View Tab -->
    </div><!-- End Tab Content -->
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
