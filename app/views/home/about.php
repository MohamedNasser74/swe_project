<?php ob_start(); ?>

<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold"><?= $page_title ?></h1>
        <p class="lead text-muted">Empowering students to achieve their career dreams since 2020</p>
    </div>

    <!-- Mission Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="bg-primary bg-opacity-10 rounded-4 p-5 text-center">
                <i class="fas fa-bullseye fa-5x text-primary mb-3"></i>
            </div>
        </div>
        <div class="col-lg-6">
            <h2 class="mb-3">Our Mission</h2>
            <p class="lead text-muted">
                We believe every student deserves access to quality career guidance, regardless of their background 
                or circumstances.
            </p>
            <p class="text-muted">
                Our platform connects students with experienced career counselors who understand the modern job market 
                and can provide personalized guidance to help each individual reach their full potential.
            </p>
            <div class="row g-3 mt-4">
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users text-primary fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0">10,000+</h4>
                            <small class="text-muted">Students Helped</small>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-tie text-success fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0">150+</h4>
                            <small class="text-muted">Expert Counselors</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="bg-light rounded-4 p-5 mb-5">
        <h2 class="text-center mb-5">Our Core Values</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-heart fa-lg"></i>
                    </div>
                    <h5>Student-First Approach</h5>
                    <p class="text-muted">Every decision we make prioritizes student success and well-being.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-shield-alt fa-lg"></i>
                    </div>
                    <h5>Trust & Integrity</h5>
                    <p class="text-muted">We maintain the highest standards of honesty and ethical conduct.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-lightbulb fa-lg"></i>
                    </div>
                    <h5>Innovation</h5>
                    <p class="text-muted">We continuously improve our platform to deliver better outcomes.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="text-center mb-5">
        <h2 class="mb-3">Meet Our Leadership</h2>
        <p class="text-muted mb-5">Dedicated professionals committed to your success</p>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <h5 class="card-title">Dr. Sarah Johnson</h5>
                        <p class="text-muted small">Founder & CEO</p>
                        <p class="small">Former career counselor with 15+ years of experience in student development.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <h5 class="card-title">Michael Chen</h5>
                        <p class="text-muted small">Head of Counseling</p>
                        <p class="small">Passionate about empowering students through personalized career guidance.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <h5 class="card-title">Emily Rodriguez</h5>
                        <p class="text-muted small">Head of Partnerships</p>
                        <p class="small">Building relationships with top employers to create opportunities for students.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center bg-primary text-white rounded-4 p-5">
        <h3 class="mb-3">Join Our Community</h3>
        <p class="mb-4">Start your career transformation today</p>
        <a href="<?= APP_URL ?>/auth/register" class="btn btn-light btn-lg">
            <i class="fas fa-rocket me-2"></i>Get Started Free
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
