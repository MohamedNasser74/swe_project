<?php ob_start(); ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?></h1>
            <p class="text-muted mb-0">Welcome back, <?= $_SESSION['user_name'] ?>!</p>
        </div>
        <div class="text-muted">
            <i class="fas fa-calendar me-2"></i>
            <?= date('F j, Y') ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-1">Total Users</h6>
                            <h2 class="mb-0"><?= $total_users ?></h2>
                        </div>
                        <div class="text-primary-emphasis opacity-75">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-1">Students</h6>
                            <h2 class="mb-0"><?= $total_students ?></h2>
                        </div>
                        <div class="text-success-emphasis opacity-75">
                            <i class="fas fa-graduation-cap fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-1">Counselors</h6>
                            <h2 class="mb-0"><?= $total_counselors ?></h2>
                        </div>
                        <div class="text-info-emphasis opacity-75">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-1">Appointments</h6>
                            <h2 class="mb-0"><?= $total_appointments ?></h2>
                        </div>
                        <div class="text-warning-emphasis opacity-75">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Recent Users -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-plus me-2 text-primary"></i>
                            Recent Users
                        </h5>
                        <a href="<?= APP_URL ?>/admin/users" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_users)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($recent_users, 0, 5) as $user): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-<?= $user->role === 'student' ? 'success' : ($user->role === 'counselor' ? 'info' : 'primary') ?> text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-<?= $user->role === 'student' ? 'graduation-cap' : ($user->role === 'counselor' ? 'user-tie' : 'crown') ?>"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></h6>
                                            <small class="text-muted"><?= htmlspecialchars($user->email) ?></small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?= $user->role === 'student' ? 'success' : ($user->role === 'counselor' ? 'info' : 'primary') ?>">
                                            <?= ucfirst($user->role) ?>
                                        </span>
                                        <br>
                                        <small class="text-muted"><?= date('M j, Y', strtotime($user->created_at)) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No users registered yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-4 col-md-6">
                            <a href="<?= APP_URL ?>/admin/users" class="card text-decoration-none border-2 border-primary h-100">
                                <div class="card-body text-center p-4">
                                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-users fa-lg"></i>
                                    </div>
                                    <h5 class="card-title text-primary">Manage Users</h5>
                                    <p class="text-muted small mb-0">View and manage all users</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <a href="<?= APP_URL ?>/admin/appointments" class="card text-decoration-none border-2 border-success h-100">
                                <div class="card-body text-center p-4">
                                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-calendar-check fa-lg"></i>
                                    </div>
                                    <h5 class="card-title text-success">Appointments</h5>
                                    <p class="text-muted small mb-0">View all appointments</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <a href="<?= APP_URL ?>/admin/users" class="card text-decoration-none border-2 border-info h-100">
                                <div class="card-body text-center p-4">
                                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-chart-line fa-lg"></i>
                                    </div>
                                    <h5 class="card-title text-info">Reports</h5>
                                    <p class="text-muted small mb-0">View basic platform reports</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>