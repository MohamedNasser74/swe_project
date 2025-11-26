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

    <!-- Quick Actions Row -->
    <div class="row g-4 mb-4 justify-content-center">
        <div class="col-lg-5 col-md-6">
            <a href="<?= APP_URL ?>/student/book-appointment" class="card text-decoration-none h-100 border-2 border-primary">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-calendar-plus fa-lg"></i>
                    </div>
                    <h5 class="card-title text-primary">Book Session</h5>
                    <p class="text-muted small mb-0">Schedule a counseling session</p>
                </div>
            </a>
        </div>
        
        <div class="col-lg-5 col-md-6">
            <a href="<?= APP_URL ?>/student/job-search" class="card text-decoration-none h-100 border-2 border-info">
                <div class="card-body text-center">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-search fa-lg"></i>
                    </div>
                    <h5 class="card-title text-info">Job Search</h5>
                    <p class="text-muted small mb-0">Find your dream job</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Upcoming Appointments -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-check me-2 text-primary"></i>
                            Upcoming Appointments
                        </h5>
                        <a href="<?= APP_URL ?>/student/appointments" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($upcoming_appointments)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($upcoming_appointments as $appointment): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($appointment->other_user_name ?? 'Counselor') ?></h6>
                                            <p class="mb-1 text-muted small">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= date('M j, Y', strtotime($appointment->appointment_date)) ?>
                                                <i class="fas fa-clock ms-2 me-1"></i>
                                                <?= date('g:i A', strtotime($appointment->appointment_time)) ?>
                                            </p>
                                            <span class="badge bg-<?= $appointment->session_type === 'career_guidance' ? 'primary' : ($appointment->session_type === 'interview_prep' ? 'success' : 'info') ?> small">
                                                <?= ucwords(str_replace('_', ' ', $appointment->session_type)) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-warning text-dark">
                                            <?= ucfirst($appointment->status) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted mb-3">No Upcoming Appointments</h5>
                            <p class="text-muted mb-4">Ready to take the next step in your career journey?</p>
                            <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Book Your First Session
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Stats & Profile -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2 text-success"></i>
                        Your Profile
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-graduation-cap fa-2x"></i>
                    </div>
                    <h5 class="mb-2"><?= $_SESSION['user_name'] ?></h5>
                    <p class="text-muted mb-3"><?= $_SESSION['user_email'] ?></p>
                    <a href="<?= APP_URL ?>/student/profile" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit Profile
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2 text-info"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_appointments)): ?>
                        <div class="timeline">
                            <?php foreach (array_slice($recent_appointments, 0, 3) as $appointment): ?>
                                <div class="timeline-item mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 12px;">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 small">Session with <?= htmlspecialchars($appointment->counselor_name ?? 'Counselor') ?></h6>
                                            <p class="text-muted small mb-1">
                                                <?= date('M j, Y', strtotime($appointment->appointment_date)) ?>
                                            </p>
                                            <span class="badge bg-<?= $appointment->status === 'completed' ? 'success' : ($appointment->status === 'scheduled' ? 'warning' : 'secondary') ?> small">
                                                <?= ucfirst($appointment->status) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?= APP_URL ?>/student/appointments" class="btn btn-outline-info btn-sm">
                                View All Appointments
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-history fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">No recent activity</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Career Resources Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2 text-warning"></i>
                        Career Resources
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-handshake fa-2x text-success mb-2"></i>
                                <h6>Interview Tips</h6>
                                <p class="text-muted small">Ace your interviews</p>
                                <a href="<?= APP_URL ?>/student/interview-tips" class="btn btn-outline-success btn-sm">Learn More</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-briefcase fa-2x text-primary mb-2"></i>
                                <h6>Job Opportunities</h6>
                                <p class="text-muted small">Find your next role</p>
                                <a href="<?= APP_URL ?>/student/job-opportunities" class="btn btn-outline-primary btn-sm">Search Jobs</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-certificate fa-2x text-info mb-2"></i>
                                <h6>Skill Development</h6>
                                <p class="text-muted small">Enhance your skills</p>
                                <a href="<?= APP_URL ?>/student/skill-development" class="btn btn-outline-info btn-sm">Get Started</a>
                            </div>
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