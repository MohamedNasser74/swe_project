<?php ob_start(); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <div class="mb-4">
                <i class="fas fa-lock fa-5x text-danger"></i>
            </div>
            <h1 class="display-4 fw-bold text-danger">Access Denied</h1>
            <p class="lead text-muted mb-4">
                Sorry, you don't have permission to access this page. 
                Please log in with an account that has the required permissions.
            </p>
            
            <div class="d-flex justify-content-center gap-3">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'student'): ?>
                        <a href="<?= APP_URL ?>/student/dashboard" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                    <?php elseif ($_SESSION['user_role'] === 'counselor'): ?>
                        <a href="<?= APP_URL ?>/counselor/dashboard" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                    <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="<?= APP_URL ?>/admin/dashboard" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/auth/login" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Log In
                    </a>
                <?php endif; ?>
                <a href="<?= APP_URL ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-2"></i>Go Home
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
