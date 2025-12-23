<?php ob_start(); ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?></h1>
            <p class="text-muted mb-0">Invest in yourself and unlock your career potential</p>
        </div>
        <div>
            <a href="<?= APP_URL ?>/student/dashboard" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Introduction Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center py-5">
                    <div class="text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%;">
                        <i class="fas fa-certificate fa-2x"></i>
                    </div>
                    <h3 class="mb-3">Continuous Learning for Career Success</h3>
                    <p class="mb-0">Develop in-demand skills and stay competitive in today's job market</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Learning Paths -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">
                <i class="fas fa-route me-2 text-primary"></i>Learning Paths
            </h4>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-code me-2"></i>Technology & Development
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Web Development
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Mobile App Development
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Data Science & Analytics
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Cloud Computing
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Cybersecurity
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Business & Management
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Project Management
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Business Analytics
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Leadership Skills
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Entrepreneurship
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Strategic Planning
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-paint-brush me-2"></i>Creative & Design
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>UI/UX Design
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Graphic Design
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Digital Marketing
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Content Creation
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Video Production
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Courses -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">
                <i class="fas fa-star text-warning me-2"></i>Featured Courses
            </h4>
        </div>

        <!-- Course 1 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <div class="card-img-top bg-primary d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fas fa-laptop-code fa-4x text-white"></i>
                    </div>
                    <span class="badge bg-warning position-absolute top-0 end-0 m-2">Bestseller</span>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-primary">Web Development</span>
                        <span class="badge bg-light text-dark">Beginner</span>
                    </div>
                    <h5 class="card-title">Full Stack Web Development</h5>
                    <p class="text-muted small mb-3">Learn to build complete web applications from front-end to back-end</p>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-muted me-2"></i>
                            <span class="text-muted small">40 hours</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users text-muted me-2"></i>
                            <span class="text-muted small">1,254 students enrolled</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star-half-alt text-warning me-2"></i>
                            <span class="text-muted small">4.5 (320 reviews)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="h5 text-success mb-0">$49.99</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course 2 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <div class="card-img-top bg-success d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fas fa-chart-bar fa-4x text-white"></i>
                    </div>
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">Hot</span>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-success">Data Science</span>
                        <span class="badge bg-light text-dark">Intermediate</span>
                    </div>
                    <h5 class="card-title">Data Science & Analytics</h5>
                    <p class="text-muted small mb-3">Master data analysis, visualization, and machine learning fundamentals</p>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-muted me-2"></i>
                            <span class="text-muted small">35 hours</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users text-muted me-2"></i>
                            <span class="text-muted small">892 students enrolled</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-2"></i>
                            <span class="text-muted small">4.8 (215 reviews)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="h5 text-success mb-0">$59.99</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course 3 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <div class="card-img-top bg-info d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fas fa-palette fa-4x text-white"></i>
                    </div>
                    <span class="badge bg-warning position-absolute top-0 end-0 m-2">New</span>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-info">Design</span>
                        <span class="badge bg-light text-dark">All Levels</span>
                    </div>
                    <h5 class="card-title">UI/UX Design Masterclass</h5>
                    <p class="text-muted small mb-3">Create stunning user interfaces and exceptional user experiences</p>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-muted me-2"></i>
                            <span class="text-muted small">28 hours</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users text-muted me-2"></i>
                            <span class="text-muted small">1,567 students enrolled</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-2"></i>
                            <span class="text-muted small">4.9 (412 reviews)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="h5 text-success mb-0">$44.99</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course 4 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <div class="card-img-top bg-warning d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fas fa-tasks fa-4x text-white"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-warning text-dark">Management</span>
                        <span class="badge bg-light text-dark">Intermediate</span>
                    </div>
                    <h5 class="card-title">Project Management Professional</h5>
                    <p class="text-muted small mb-3">Master project management methodologies and lead successful projects</p>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-muted me-2"></i>
                            <span class="text-muted small">32 hours</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users text-muted me-2"></i>
                            <span class="text-muted small">743 students enrolled</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star-half-alt text-warning me-2"></i>
                            <span class="text-muted small">4.6 (178 reviews)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="h5 text-success mb-0">$54.99</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course 5 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <div class="card-img-top bg-danger d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fas fa-bullhorn fa-4x text-white"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-danger">Marketing</span>
                        <span class="badge bg-light text-dark">Beginner</span>
                    </div>
                    <h5 class="card-title">Digital Marketing Essentials</h5>
                    <p class="text-muted small mb-3">Learn SEO, social media marketing, and content strategy</p>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-muted me-2"></i>
                            <span class="text-muted small">25 hours</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users text-muted me-2"></i>
                            <span class="text-muted small">2,103 students enrolled</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-2"></i>
                            <span class="text-muted small">4.7 (534 reviews)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="h5 text-success mb-0">$39.99</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course 6 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fas fa-shield-alt fa-4x text-white"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-secondary">Security</span>
                        <span class="badge bg-light text-dark">Advanced</span>
                    </div>
                    <h5 class="card-title">Cybersecurity Fundamentals</h5>
                    <p class="text-muted small mb-3">Learn to protect systems and data from cyber threats</p>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-muted me-2"></i>
                            <span class="text-muted small">38 hours</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users text-muted me-2"></i>
                            <span class="text-muted small">621 students enrolled</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-1"></i>
                            <i class="fas fa-star text-warning me-2"></i>
                            <span class="text-muted small">4.8 (156 reviews)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="h5 text-success mb-0">$64.99</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Soft Skills Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">
                <i class="fas fa-users me-2 text-success"></i>Essential Soft Skills
            </h4>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-comment fa-3x text-primary mb-3"></i>
                    <h5>Communication</h5>
                    <p class="text-muted small mb-3">Master verbal and written communication</p>
                    <button class="btn btn-outline-primary btn-sm">Start Learning</button>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-users-cog fa-3x text-success mb-3"></i>
                    <h5>Leadership</h5>
                    <p class="text-muted small mb-3">Develop leadership and team management</p>
                    <button class="btn btn-outline-success btn-sm">Start Learning</button>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-lightbulb fa-3x text-warning mb-3"></i>
                    <h5>Problem Solving</h5>
                    <p class="text-muted small mb-3">Enhance critical thinking abilities</p>
                    <button class="btn btn-outline-warning btn-sm">Start Learning</button>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-clock fa-3x text-info mb-3"></i>
                    <h5>Time Management</h5>
                    <p class="text-muted small mb-3">Optimize productivity and efficiency</p>
                    <button class="btn btn-outline-info btn-sm">Start Learning</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Free Resources -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-gift me-2"></i>Free Learning Resources
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-book text-success fa-2x me-3"></i>
                                <div>
                                    <h6>E-Books & Guides</h6>
                                    <p class="text-muted small mb-2">Access our library of career development e-books and comprehensive guides</p>
                                    <a href="#" class="btn btn-sm btn-outline-success">Browse Library</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-video text-info fa-2x me-3"></i>
                                <div>
                                    <h6>Video Tutorials</h6>
                                    <p class="text-muted small mb-2">Watch free video tutorials on various career and skill development topics</p>
                                    <a href="#" class="btn btn-sm btn-outline-info">Watch Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-podcast text-warning fa-2x me-3"></i>
                                <div>
                                    <h6>Podcasts & Webinars</h6>
                                    <p class="text-muted small mb-2">Listen to industry experts share insights and career advice</p>
                                    <a href="#" class="btn btn-sm btn-outline-warning">Listen</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-newspaper text-primary fa-2x me-3"></i>
                                <div>
                                    <h6>Articles & Blog Posts</h6>
                                    <p class="text-muted small mb-2">Read the latest articles on career trends and professional development</p>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certification Programs -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">
                <i class="fas fa-award me-2 text-warning"></i>Professional Certifications
            </h4>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-certificate fa-2x"></i>
                    </div>
                    <h5>Google Certifications</h5>
                    <p class="text-muted small">Get certified in Digital Marketing, Data Analytics, and more</p>
                    <button class="btn btn-outline-warning">View Programs</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-graduation-cap fa-2x"></i>
                    </div>
                    <h5>Microsoft Certifications</h5>
                    <p class="text-muted small">Earn industry-recognized Microsoft technology certifications</p>
                    <button class="btn btn-outline-primary">View Programs</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                    <h5>Industry Certifications</h5>
                    <p class="text-muted small">Explore PMP, AWS, CompTIA, and other professional certifications</p>
                    <button class="btn btn-outline-danger">View Programs</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Get Help CTA -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                    <h3 class="mb-3">Need Guidance on Your Learning Path?</h3>
                    <p class="mb-4">Our career counselors can help you choose the right skills to develop for your career goals</p>
                    <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-light btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Schedule a Consultation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
