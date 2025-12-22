<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Shape Your Future with Expert Career Guidance</h1>
                <p class="lead mb-4">Connect with professional career counselors and unlock your potential with personalized career development resources.</p>
                <div class="d-flex flex-wrap gap-3">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="<?= APP_URL ?>/auth/register" class="btn btn-light btn-lg">Get Started Free</a>
                        <a href="<?= APP_URL ?>/auth/login" class="btn btn-outline-light btn-lg">Sign In</a>
                    <?php else: ?>
                        <?php if ($_SESSION['user_role'] === 'student'): ?>
                            <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-light btn-lg">Book Session</a>
                            <a href="<?= APP_URL ?>/student/dashboard" class="btn btn-outline-light btn-lg">Dashboard</a>
                        <?php else: ?>
                            <a href="<?= APP_URL ?>/<?= $_SESSION['user_role'] ?>/dashboard" class="btn btn-light btn-lg">Go to Dashboard</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Automated Slideshow -->
                <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?= APP_URL ?>/images/slideshow/slide1.jpg" class="d-block w-100 img-fluid rounded shadow slideshow-img" alt="Virtual Career Platform" onerror="this.src='https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1171&q=80'">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="bg-gradient bg-opacity-75 rounded p-3" style="background: linear-gradient(135deg, rgba(0,123,255,0.9) 0%, rgba(102,126,234,0.9) 100%);">
                                    <h5 class="fw-bold">Virtual Career Platform</h5>
                                    <p class="mb-1">Unlock Your Potential. Connect. Grow.</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="<?= APP_URL ?>/images/slideshow/slide2.jpg" class="d-block w-100 img-fluid rounded shadow slideshow-img" alt="Expert Career Guidance" onerror="this.src='https://images.unsplash.com/photo-1560472355-536de3962603?ixlib=rb-4.0.3&auto=format&fit=crop&w=1126&q=80'">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="bg-gradient bg-opacity-75 rounded p-3" style="background: linear-gradient(135deg, rgba(40,167,69,0.9) 0%, rgba(25,135,84,0.9) 100%);">
                                    <h5 class="fw-bold">Expert Career Guidance</h5>
                                    <p class="mb-1">Get personalized advice from certified counselors</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="<?= APP_URL ?>/images/slideshow/slide3.jpg" class="d-block w-100 img-fluid rounded shadow slideshow-img" alt="Resume Building & Interview Prep" onerror="this.src='https://images.unsplash.com/photo-1586880244386-8b3e34c8382c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80'">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="bg-gradient bg-opacity-75 rounded p-3" style="background: linear-gradient(135deg, rgba(23,162,184,0.9) 0%, rgba(13,110,253,0.9) 100%);">
                                    <h5 class="fw-bold">Interview Preparation</h5>
                                    <p class="mb-1">Ace your interviews with expert guidance</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="<?= APP_URL ?>/images/slideshow/slide4.jpg" class="d-block w-100 img-fluid rounded shadow slideshow-img" alt="Career Success Network" onerror="this.src='https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80'">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="bg-gradient bg-opacity-75 rounded p-3" style="background: linear-gradient(135deg, rgba(255,193,7,0.9) 0%, rgba(255,143,0,0.9) 100%);">
                                    <h5 class="fw-bold">Career Success Network</h5>
                                    <p class="mb-1">Connect with professionals and find your dream job</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3">Everything You Need for Career Success</h2>
                <p class="lead text-muted">Our comprehensive platform provides all the tools and guidance you need to navigate your career journey successfully.</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Expert Career Counseling</h5>
                        <p class="text-muted">Connect with certified career counselors for personalized guidance, career planning, and professional development advice.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Interview Preparation</h5>
                        <p class="text-muted">Get expert guidance and practice sessions to ace your interviews. Build confidence and improve your interview skills.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-handshake fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Interview Preparation</h5>
                        <p class="text-muted">Practice with mock interviews, get personalized feedback, and learn proven strategies to ace your job interviews.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-robot fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-3">AI Career Predictor</h5>
                        <p class="text-muted">Discover your ideal career path using our machine learning-based predictor that analyzes your skills and recommends roles.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Career Guidance</h5>
                        <p class="text-muted">Get personalized career advice from experienced counselors. Plan your career path with expert guidance and support.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-certificate fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Career Resources</h5>
                        <p class="text-muted">Access comprehensive career development resources, guides, and tools to advance your professional journey.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="h2 fw-bold text-primary mb-2">1000+</div>
                <p class="text-muted mb-0">Students Guided</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="h2 fw-bold text-primary mb-2">50+</div>
                <p class="text-muted mb-0">Expert Counselors</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="h2 fw-bold text-primary mb-2">5000+</div>
                <p class="text-muted mb-0">Career Sessions</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="h2 fw-bold text-primary mb-2">95%</div>
                <p class="text-muted mb-0">Success Rate</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-4">Ready to Transform Your Career?</h2>
                <p class="lead mb-4">Join thousands of students who have successfully launched their careers with our expert guidance and comprehensive resources.</p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="<?= APP_URL ?>/auth/register" class="btn btn-light btn-lg me-3">Start Your Journey</a>
                    <a href="<?= APP_URL ?>/home/learn-more" class="btn btn-outline-light btn-lg">Learn More</a>
                <?php elseif ($_SESSION['user_role'] === 'student'): ?>
                    <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-light btn-lg me-3">Book Your Session</a>
                    <a href="<?= APP_URL ?>/home/services" class="btn btn-outline-light btn-lg">Explore Services</a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/<?= $_SESSION['user_role'] ?>/dashboard" class="btn btn-light btn-lg me-3">Go to Dashboard</a>
                    <a href="<?= APP_URL ?>/home/services" class="btn btn-outline-light btn-lg">Explore Services</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>