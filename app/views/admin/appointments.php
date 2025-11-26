<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?? 'Appointment Management' ?></h1>
            <p class="text-muted mb-0">View and monitor all counseling appointments.</p>
        </div>
        <div class="text-muted">
            <i class="fas fa-calendar-check me-2"></i>
            <?= is_array($appointments ?? null) ? count($appointments) : 0 ?> appointments
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-alt me-2 text-success"></i>
                All Appointments
            </h5>
            <form class="d-flex flex-wrap gap-2" method="get" action="<?= APP_URL ?>/admin/appointments">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="scheduled" <?= ($filter_status ?? '') === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                    <option value="confirmed" <?= ($filter_status ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="completed" <?= ($filter_status ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= ($filter_status ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <select name="counselor_id" class="form-select form-select-sm">
                    <option value="">All Counselors</option>
                    <?php foreach ($counselors as $c): ?>
                        <option value="<?= $c->id ?>" <?= ($filter_counselor_id ?? '') == $c->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c->first_name . ' ' . $c->last_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="student_id" class="form-select form-select-sm">
                    <option value="">All Students</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?= $s->id ?>" <?= ($filter_student_id ?? '') == $s->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s->first_name . ' ' . $s->last_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-primary" type="submit">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </form>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($appointments)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Student</th>
                                <th>Counselor</th>
                                <th>Session Type</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $index => $appointment): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($appointment->appointment_date) ?></td>
                                    <td><?= htmlspecialchars($appointment->appointment_time) ?></td>
                                    <td><?= htmlspecialchars($appointment->student_name ?? ('ID: ' . $appointment->student_id)) ?></td>
                                    <td><?= htmlspecialchars($appointment->counselor_name ?? ('ID: ' . $appointment->counselor_id)) ?></td>
                                    <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $appointment->session_type))) ?></td>
                                    <td>
                                        <?php
                                            $status = $appointment->status ?? 'scheduled';
                                            $badgeClass = match ($status) {
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                default => 'primary',
                                            };
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?>">
                                            <?= ucfirst($status) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form method="post" action="<?= APP_URL ?>/admin/updateAppointment/<?= $appointment->id ?>" class="d-inline-flex gap-1">
                                            <select name="status" class="form-select form-select-sm w-auto">
                                                <option value="">-- Status --</option>
                                                <option value="scheduled">Scheduled</option>
                                                <option value="confirmed">Confirmed</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                            <select name="counselor_id" class="form-select form-select-sm w-auto">
                                                <option value="">-- Counselor --</option>
                                                <?php foreach ($counselors as $c): ?>
                                                    <option value="<?= $c->id ?>"><?= htmlspecialchars($c->first_name . ' ' . $c->last_name) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <p class="mb-0">No appointments have been scheduled yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>


