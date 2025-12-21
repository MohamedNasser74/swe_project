<?php ob_start(); ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1"><?= $page_title ?></h1>
            <p class="text-muted mb-0">Manage your personal information</p>
        </div>
        <a href="<?= APP_URL ?>/counselor/dashboard" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Profile Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Profile Information</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?= APP_URL ?>/counselor/profile">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>
                            <div class="col-md-12">
                                <label for="specialization" class="form-label">Specialization</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" 
                                       value="<?= htmlspecialchars($user['specialization'] ?? '') ?>"
                                       placeholder="e.g., Career Guidance, Interview Prep, Tech Industry">
                            </div>
                            <div class="col-12">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4" 
                                          placeholder="Tell students about your experience and expertise..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-tie fa-2x"></i>
                    </div>
                    <h5 class="mb-1"><?= htmlspecialchars($user['name'] ?? 'Counselor') ?></h5>
                    <p class="text-muted mb-3">Career Counselor</p>
                    <span class="badge bg-success">Active</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-link me-2 text-info"></i>Quick Links</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= APP_URL ?>/counselor/schedule" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Manage Schedule
                        </a>
                        <a href="<?= APP_URL ?>/counselor/appointments" class="btn btn-outline-success">
                            <i class="fas fa-calendar-check me-2"></i>View Appointments
                        </a>
                        <a href="<?= APP_URL ?>/counselor/students" class="btn btn-outline-info">
                            <i class="fas fa-users me-2"></i>My Students
                        </a>
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
