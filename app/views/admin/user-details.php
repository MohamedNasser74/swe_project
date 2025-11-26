<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?? 'User Details' ?></h1>
            <p class="text-muted mb-0">View detailed information about this user.</p>
        </div>
        <a href="<?= APP_URL ?>/admin/users" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-<?= $user->role === 'student' ? 'success' : ($user->role === 'counselor' ? 'info' : 'primary') ?> text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-<?= $user->role === 'student' ? 'graduation-cap' : ($user->role === 'counselor' ? 'user-tie' : 'crown') ?> fa-2x"></i>
                        </div>
                    </div>
                    <h3 class="mb-1"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></h3>
                    <p class="text-muted mb-2"><?= htmlspecialchars($user->email) ?></p>
                    <span class="badge bg-<?= $user->role === 'student' ? 'success' : ($user->role === 'counselor' ? 'info' : 'primary') ?>">
                        <?= ucfirst($user->role) ?>
                    </span>
                    <p class="mt-3 mb-0">
                        <small class="text-muted">Joined <?= date('F j, Y', strtotime($user->created_at)) ?></small>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-id-card me-2 text-primary"></i>
                        Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">First Name</label>
                            <p class="fw-semibold mb-0"><?= htmlspecialchars($user->first_name) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Last Name</label>
                            <p class="fw-semibold mb-0"><?= htmlspecialchars($user->last_name) ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email</label>
                            <p class="fw-semibold mb-0"><?= htmlspecialchars($user->email) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Phone</label>
                            <p class="fw-semibold mb-0"><?= htmlspecialchars($user->phone ?? 'Not provided') ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Role</label>
                            <p class="fw-semibold mb-0"><?= ucfirst($user->role) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Status</label>
                            <p class="fw-semibold mb-0"><?= ucfirst($user->status ?? 'active') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($counselor_stats)): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2 text-success"></i>
                        Counselor Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <h6 class="text-muted small">Total Sessions</h6>
                            <h4 class="mb-0"><?= (int)$counselor_stats['total_appointments'] ?></h4>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <h6 class="text-muted small">Completed</h6>
                            <h4 class="mb-0 text-success"><?= (int)$counselor_stats['completed_appointments'] ?></h4>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <h6 class="text-muted small">Pending</h6>
                            <h4 class="mb-0 text-warning"><?= (int)$counselor_stats['pending_appointments'] ?></h4>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted small">Today</h6>
                            <h4 class="mb-0 text-info"><?= (int)$counselor_stats['todays_appointments'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2 text-success"></i>
                        Recent Activity
                    </h5>
                    <span class="badge bg-secondary">Sample section (extend in project)</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        This section can be extended to show the user's recent appointments, logins, or other activity.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>


