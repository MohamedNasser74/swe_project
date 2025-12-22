<?php ob_start(); ?>

<?= CalendarHelper::getStyles() ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?></h1>
            <p class="text-muted mb-0">Manage your counseling sessions</p>
        </div>
        <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-primary">
            <i class="fas fa-calendar-plus me-2"></i>Book New Session
        </a>
    </div>

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
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <?php
                    $month = $_GET['month'] ?? date('m');
                    $year = $_GET['year'] ?? date('Y');
                    $calendar = new CalendarHelper($year, $month);
                    $calendar->setAppointments($appointments ?? []);
                    echo $calendar->render(APP_URL . '/student/appointments');
                    ?>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div class="tab-pane fade" id="listView" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        Your Appointments
                    </h5>
                </div>
                <div class="card-body">
            <?php if (!empty($appointments)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Counselor</th>
                                <th>Date & Time</th>
                                <th>Session Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= htmlspecialchars($appointment->counselor_name ?? 'Counselor') ?></h6>
                                                <small class="text-muted"><?= htmlspecialchars($appointment->specialization ?? 'Career Counselor') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= date('M j, Y', strtotime($appointment->appointment_date)) ?></strong><br>
                                            <small class="text-muted"><?= date('g:i A', strtotime($appointment->appointment_time)) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $appointment->session_type === 'career_guidance' ? 'primary' : ($appointment->session_type === 'interview_prep' ? 'success' : 'info') ?>">
                                            <?= ucwords(str_replace('_', ' ', $appointment->session_type)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $appointment->status === 'completed' ? 'success' : ($appointment->status === 'scheduled' ? 'warning' : ($appointment->status === 'confirmed' ? 'info' : 'secondary')) ?>">
                                            <?= ucfirst($appointment->status) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($appointment->status === 'scheduled' || $appointment->status === 'confirmed'): ?>
                                            <button class="btn btn-sm btn-outline-danger" onclick="cancelAppointment(<?= $appointment->id ?>)">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </button>
                                        <?php elseif ($appointment->status === 'completed' && !$appointment->rating): ?>
                                            <button class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-star me-1"></i>Rate
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">No Appointments Yet</h4>
                    <p class="text-muted mb-4">Start your career journey by booking your first counseling session.</p>
                    <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Book Your First Session
                    </a>
                </div>
            <?php endif; ?>
                </div>
            </div>
        </div><!-- End List View Tab -->
    </div><!-- End Tab Content -->
</div>

<script>
function cancelAppointment(appointmentId) {
    if (confirm('Are you sure you want to cancel this appointment?')) {
        window.location.href = '<?= APP_URL ?>/student/cancel-appointment/' + appointmentId;
    }
}
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>