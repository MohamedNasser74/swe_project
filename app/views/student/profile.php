<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?></h1>
            <p class="text-muted mb-0">Manage your personal information</p>
        </div>
    </div>

    <div class="row">
        <!-- Profile Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <div><?= htmlspecialchars($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= APP_URL ?>/student/profile">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?= htmlspecialchars($form_data['first_name'] ?? $user->first_name ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?= htmlspecialchars($form_data['last_name'] ?? $user->last_name ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($user->email ?? '') ?>" readonly>
                            <small class="text-muted">Email cannot be changed. Contact support if needed.</small>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($form_data['phone'] ?? $user->phone ?? '') ?>" 
                                   placeholder="Enter your phone number">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= APP_URL ?>/student/dashboard" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card me-2"></i>
                        Profile Summary
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                        <i class="fas fa-graduation-cap fa-3x"></i>
                    </div>
                    <h4 class="mb-2"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></h4>
                    <p class="text-muted mb-2"><?= htmlspecialchars($user->email) ?></p>
                    <span class="badge bg-success mb-3">Student</span>
                    
                    <?php if ($user->phone): ?>
                        <p class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <?= htmlspecialchars($user->phone) ?>
                        </p>
                    <?php endif; ?>
                    
                    <p class="text-muted small">
                        <i class="fas fa-calendar me-2"></i>
                        Member since <?= date('F Y', strtotime($user->created_at)) ?>
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Book Session
                        </a>
                        <a href="<?= APP_URL ?>/student/job-search" class="btn btn-outline-info">
                            <i class="fas fa-search me-2"></i>Job Search
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