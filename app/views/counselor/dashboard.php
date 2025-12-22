<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0">Welcome back, <?= $_SESSION['user_name'] ?>!</h1>
            <p class="text-muted mb-0">Here's your counseling dashboard overview</p>
        </div>
        <div>
            <span class="badge bg-success fs-6">
                <i class="fas fa-circle me-2"></i>Online
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Appointments</h6>
                            <h3 class="mb-0"><?= $stats['total_appointments'] ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Completed Sessions</h6>
                            <h3 class="mb-0"><?= $stats['completed_appointments'] ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h3 class="mb-0"><?= $stats['pending_appointments'] ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                <i class="fas fa-calendar-day fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Today's Sessions</h6>
                            <h3 class="mb-0"><?= $stats['todays_appointments'] ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Today's Appointments -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-day me-2 text-primary"></i>
                            Today's Appointments
                        </h5>
                        <a href="<?= APP_URL ?>/counselor/appointments" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($todays_appointments)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($todays_appointments as $appointment): ?>
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1"><?= htmlspecialchars($appointment->student_name ?? 'Unknown Student') ?></h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?= date('g:i A', strtotime($appointment->appointment_time)) ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <p class="mb-2 text-muted small">
                                                <strong>Type:</strong> <?= ucwords(str_replace('-', ' ', $appointment->session_type)) ?>
                                            </p>
                                            <?php if (!empty($appointment->notes)): ?>
                                                <p class="mb-2 small"><?= htmlspecialchars($appointment->notes) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="badge bg-<?= $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'secondary') ?>">
                                                <?= ucfirst($appointment->status) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No appointments scheduled for today</h6>
                            <p class="text-muted small">Enjoy your day!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Upcoming -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= APP_URL ?>/counselor/schedule" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Manage Schedule
                        </a>
                        <a href="<?= APP_URL ?>/counselor/appointments" class="btn btn-outline-success">
                            <i class="fas fa-eye me-2"></i>View Appointments
                        </a>
                        <a href="<?= APP_URL ?>/counselor/students" class="btn btn-outline-info">
                            <i class="fas fa-users me-2"></i>My Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pending Appointments -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2 text-warning"></i>
                        Pending Appointments
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($pending_appointments)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($pending_appointments, 0, 3) as $appointment): ?>
                                <div class="list-group-item border-0 px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 small"><?= htmlspecialchars($appointment->student_name ?? 'Unknown') ?></h6>
                                            <small class="text-muted">
                                                <?= date('M j, g:i A', strtotime($appointment->appointment_date . ' ' . $appointment->appointment_time)) ?>
                                            </small>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-success" onclick="updateAppointmentStatus(<?= $appointment->id ?>, 'confirmed')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="updateAppointmentStatus(<?= $appointment->id ?>, 'cancelled')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($pending_appointments) > 3): ?>
                            <div class="text-center mt-3">
                                <a href="<?= APP_URL ?>/counselor/appointments?status=pending" class="btn btn-sm btn-outline-primary">
                                    View All Pending (<?= count($pending_appointments) ?>)
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <p class="text-muted small mb-0">No pending appointments</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming This Week -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-week me-2 text-info"></i>
                        Upcoming This Week
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($upcoming_appointments)): ?>
                        <div class="row g-3">
                            <?php foreach ($upcoming_appointments as $appointment): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border-1">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="mb-0"><?= htmlspecialchars($appointment->student_name ?? 'Unknown') ?></h6>
                                                <span class="badge bg-<?= $appointment->status === 'confirmed' ? 'success' : 'warning' ?> small">
                                                    <?= ucfirst($appointment->status) ?>
                                                </span>
                                            </div>
                                            <p class="text-muted small mb-2">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= date('M j, Y', strtotime($appointment->appointment_date)) ?>
                                            </p>
                                            <p class="text-muted small mb-2">
                                                <i class="fas fa-clock me-1"></i>
                                                <?= date('g:i A', strtotime($appointment->appointment_time)) ?>
                                            </p>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-tag me-1"></i>
                                                <?= ucwords(str_replace('-', ' ', $appointment->session_type)) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No upcoming appointments this week</h6>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateAppointmentStatus(appointmentId, status) {
    if (confirm(`Are you sure you want to ${status === 'confirmed' ? 'confirm' : 'cancel'} this appointment?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= APP_URL ?>/counselor/updateAppointmentStatus';
        
        const appointmentInput = document.createElement('input');
        appointmentInput.type = 'hidden';
        appointmentInput.name = 'appointment_id';
        appointmentInput.value = appointmentId;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        
        form.appendChild(appointmentInput);
        form.appendChild(statusInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>