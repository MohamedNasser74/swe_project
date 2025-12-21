<?php ob_start(); ?>

<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold"><?= $page_title ?></h1>
        <p class="lead text-muted">Comprehensive support to help you achieve your career goals</p>
    </div>

    <!-- Services Grid -->
    <div class="row g-4 mb-5">
        <!-- Career Guidance -->
        <div class="col-lg-4" id="career-guidance">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-compass fa-2x text-primary"></i>
                    </div>
                    <h3 class="card-title h4">Career Guidance</h3>
                    <p class="card-text text-muted">
                        Get personalized advice on career paths, industry insights, and strategic planning 
                        to help you make informed decisions about your professional future.
                    </p>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Career path assessment</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Industry trend analysis</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Skills gap identification</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Action plan development</li>
                    </ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-primary mt-3">
                            <i class="fas fa-calendar-plus me-2"></i>Book Session
                        </a>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/auth/register" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Interview Prep -->
        <div class="col-lg-4" id="interview-prep">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body text-center p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-tie fa-2x text-success"></i>
                    </div>
                    <h3 class="card-title h4">Interview Preparation</h3>
                    <p class="card-text text-muted">
                        Master the art of interviewing with mock sessions, feedback from industry experts, 
                        and proven techniques to stand out from other candidates.
                    </p>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Mock interview sessions</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Behavioral question training</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Technical interview prep</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Personalized feedback</li>
                    </ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-success mt-3">
                            <i class="fas fa-calendar-plus me-2"></i>Book Session
                        </a>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/auth/register" class="btn btn-outline-success mt-3">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Job Search -->
        <div class="col-lg-4" id="job-search">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body text-center p-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-search fa-2x text-info"></i>
                    </div>
                    <h3 class="card-title h4">Job Search Support</h3>
                    <p class="card-text text-muted">
                        Navigate the job market effectively with resume optimization, networking strategies, 
                        and access to exclusive job opportunities.
                    </p>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Resume & cover letter review</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>LinkedIn optimization</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Job search strategies</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Networking guidance</li>
                    </ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-info mt-3">
                            <i class="fas fa-calendar-plus me-2"></i>Book Session
                        </a>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/auth/register" class="btn btn-outline-info mt-3">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="bg-light rounded-4 p-5 mb-5">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <span class="h4 mb-0">1</span>
                </div>
                <h5>Create Account</h5>
                <p class="text-muted small">Sign up for free and complete your profile</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <span class="h4 mb-0">2</span>
                </div>
                <h5>Choose Service</h5>
                <p class="text-muted small">Select the type of counseling you need</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <span class="h4 mb-0">3</span>
                </div>
                <h5>Book Session</h5>
                <p class="text-muted small">Schedule with an expert counselor</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <span class="h4 mb-0">4</span>
                </div>
                <h5>Achieve Goals</h5>
                <p class="text-muted small">Get guidance and land your dream job</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="text-center">
        <h3 class="mb-3">Ready to Take the Next Step?</h3>
        <p class="text-muted mb-4">Join thousands of students who have transformed their careers with our platform</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="<?= APP_URL ?>/auth/register" class="btn btn-primary btn-lg me-2">
                <i class="fas fa-rocket me-2"></i>Start Free
            </a>
        <?php endif; ?>
        <a href="<?= APP_URL ?>/home/contact" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-envelope me-2"></i>Contact Us
        </a>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
</style>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
