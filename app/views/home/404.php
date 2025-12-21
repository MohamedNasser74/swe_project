<?php ob_start(); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <div class="mb-4">
                <span class="display-1 fw-bold text-primary">404</span>
            </div>
            <h1 class="h2 fw-bold mb-3">Page Not Found</h1>
            <p class="lead text-muted mb-4">
                Oops! The page you're looking for doesn't exist or has been moved.
                Don't worry, let's get you back on track.
            </p>
            
            <div class="d-flex justify-content-center gap-3 mb-5">
                <a href="<?= APP_URL ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-home me-2"></i>Go Home
                </a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Go Back
                </a>
            </div>

            <!-- Helpful Links -->
            <div class="bg-light rounded-4 p-4">
                <h5 class="mb-3">Helpful Links</h5>
                <div class="row g-3">
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/home/services" class="text-decoration-none">
                            <i class="fas fa-briefcase text-primary me-2"></i>Our Services
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/home/contact" class="text-decoration-none">
                            <i class="fas fa-envelope text-primary me-2"></i>Contact Us
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/auth/login" class="text-decoration-none">
                            <i class="fas fa-sign-in-alt text-primary me-2"></i>Login
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/auth/register" class="text-decoration-none">
                            <i class="fas fa-user-plus text-primary me-2"></i>Register
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
