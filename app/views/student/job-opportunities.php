<?php ob_start(); ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?></h1>
            <p class="text-muted mb-0">Explore exciting career opportunities tailored for you</p>
        </div>
        <div>
            <a href="<?= APP_URL ?>/student/dashboard" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Search and Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="keyword" class="form-label">
                                <i class="fas fa-search me-1"></i>Search Keywords
                            </label>
                            <input type="text" class="form-control" id="keyword" placeholder="Job title, skills, company...">
                        </div>
                        <div class="col-md-3">
                            <label for="location" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Location
                            </label>
                            <input type="text" class="form-control" id="location" placeholder="City, state, remote...">
                        </div>
                        <div class="col-md-3">
                            <label for="category" class="form-label">
                                <i class="fas fa-briefcase me-1"></i>Category
                            </label>
                            <select class="form-select" id="category">
                                <option value="">All Categories</option>
                                <option value="technology">Technology</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="finance">Finance</option>
                                <option value="education">Education</option>
                                <option value="marketing">Marketing</option>
                                <option value="engineering">Engineering</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Opportunities -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="fas fa-star text-warning me-2"></i>Featured Opportunities
                </h4>
                <a href="<?= APP_URL ?>/student/job-search" class="btn btn-sm btn-outline-primary">
                    View All Jobs
                </a>
            </div>
        </div>
    </div>

    <!-- Job Listings -->
    <div class="row g-4">
        <!-- Job 1 -->
        <div class="col-lg-6">
            <div class="card h-100 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-code fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Software Developer</h5>
                                <p class="text-muted mb-2">TechCorp Solutions</p>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>San Francisco, CA
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-clock me-1"></i>Full-time
                                </p>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark">Featured</span>
                    </div>
                    <p class="text-muted mb-3">
                        Join our innovative team to build cutting-edge web applications. Looking for passionate developers with strong problem-solving skills.
                    </p>
                    <div class="mb-3">
                        <span class="badge bg-light text-dark me-2">JavaScript</span>
                        <span class="badge bg-light text-dark me-2">React</span>
                        <span class="badge bg-light text-dark me-2">Node.js</span>
                        <span class="badge bg-light text-dark">Python</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success fw-bold">
                            <i class="fas fa-dollar-sign me-1"></i>$80,000 - $120,000/year
                        </span>
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job 2 -->
        <div class="col-lg-6">
            <div class="card h-100 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-start">
                            <div class="bg-info text-white rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-chart-line fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Marketing Manager</h5>
                                <p class="text-muted mb-2">Creative Agency Inc.</p>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>New York, NY
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-clock me-1"></i>Full-time
                                </p>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark">Featured</span>
                    </div>
                    <p class="text-muted mb-3">
                        Lead our marketing initiatives and drive brand growth. Perfect opportunity for creative minds with strategic thinking.
                    </p>
                    <div class="mb-3">
                        <span class="badge bg-light text-dark me-2">SEO</span>
                        <span class="badge bg-light text-dark me-2">Social Media</span>
                        <span class="badge bg-light text-dark me-2">Content Strategy</span>
                        <span class="badge bg-light text-dark">Analytics</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success fw-bold">
                            <i class="fas fa-dollar-sign me-1"></i>$70,000 - $95,000/year
                        </span>
                        <button class="btn btn-info btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job 3 -->
        <div class="col-lg-6">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-start">
                            <div class="bg-success text-white rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-paint-brush fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">UX/UI Designer</h5>
                                <p class="text-muted mb-2">Design Studio Pro</p>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>Remote
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-clock me-1"></i>Full-time
                                </p>
                            </div>
                        </div>
                        <span class="badge bg-success text-white">Remote</span>
                    </div>
                    <p class="text-muted mb-3">
                        Create beautiful and intuitive user experiences. Work with cross-functional teams to design innovative digital products.
                    </p>
                    <div class="mb-3">
                        <span class="badge bg-light text-dark me-2">Figma</span>
                        <span class="badge bg-light text-dark me-2">Adobe XD</span>
                        <span class="badge bg-light text-dark me-2">Sketch</span>
                        <span class="badge bg-light text-dark">Prototyping</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success fw-bold">
                            <i class="fas fa-dollar-sign me-1"></i>$75,000 - $105,000/year
                        </span>
                        <button class="btn btn-success btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job 4 -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-start">
                            <div class="bg-warning text-dark rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-database fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Data Analyst</h5>
                                <p class="text-muted mb-2">Analytics Corp</p>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>Chicago, IL
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-clock me-1"></i>Full-time
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-3">
                        Transform data into actionable insights. Help drive business decisions through advanced analytics and visualization.
                    </p>
                    <div class="mb-3">
                        <span class="badge bg-light text-dark me-2">SQL</span>
                        <span class="badge bg-light text-dark me-2">Python</span>
                        <span class="badge bg-light text-dark me-2">Tableau</span>
                        <span class="badge bg-light text-dark">Excel</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success fw-bold">
                            <i class="fas fa-dollar-sign me-1"></i>$65,000 - $90,000/year
                        </span>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job 5 -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-start">
                            <div class="bg-danger text-white rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-project-diagram fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Project Manager</h5>
                                <p class="text-muted mb-2">Enterprise Solutions Ltd</p>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>Boston, MA
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-clock me-1"></i>Full-time
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-3">
                        Lead complex projects from conception to delivery. Work with diverse teams to ensure successful project outcomes.
                    </p>
                    <div class="mb-3">
                        <span class="badge bg-light text-dark me-2">Agile</span>
                        <span class="badge bg-light text-dark me-2">Scrum</span>
                        <span class="badge bg-light text-dark me-2">JIRA</span>
                        <span class="badge bg-light text-dark">Leadership</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success fw-bold">
                            <i class="fas fa-dollar-sign me-1"></i>$85,000 - $115,000/year
                        </span>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job 6 -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-start">
                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Junior Developer Intern</h5>
                                <p class="text-muted mb-2">StartUp Hub</p>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>Austin, TX
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-clock me-1"></i>Internship
                                </p>
                            </div>
                        </div>
                        <span class="badge bg-info">Entry Level</span>
                    </div>
                    <p class="text-muted mb-3">
                        Perfect opportunity for students and recent graduates. Gain hands-on experience in a dynamic startup environment.
                    </p>
                    <div class="mb-3">
                        <span class="badge bg-light text-dark me-2">HTML/CSS</span>
                        <span class="badge bg-light text-dark me-2">JavaScript</span>
                        <span class="badge bg-light text-dark me-2">Git</span>
                        <span class="badge bg-light text-dark">Teamwork</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success fw-bold">
                            <i class="fas fa-dollar-sign me-1"></i>$20/hour
                        </span>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Industry Categories -->
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-4">
                <i class="fas fa-industry me-2 text-primary"></i>Browse by Industry
            </h4>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-laptop-code fa-3x text-primary mb-3"></i>
                    <h5>Technology</h5>
                    <p class="text-muted">125 Opportunities</p>
                    <button class="btn btn-outline-primary btn-sm">Explore</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-heartbeat fa-3x text-danger mb-3"></i>
                    <h5>Healthcare</h5>
                    <p class="text-muted">87 Opportunities</p>
                    <button class="btn btn-outline-danger btn-sm">Explore</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-chart-bar fa-3x text-success mb-3"></i>
                    <h5>Finance</h5>
                    <p class="text-muted">95 Opportunities</p>
                    <button class="btn btn-outline-success btn-sm">Explore</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-graduation-cap fa-3x text-info mb-3"></i>
                    <h5>Education</h5>
                    <p class="text-muted">62 Opportunities</p>
                    <button class="btn btn-outline-info btn-sm">Explore</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-bullhorn fa-3x text-warning mb-3"></i>
                    <h5>Marketing</h5>
                    <p class="text-muted">73 Opportunities</p>
                    <button class="btn btn-outline-warning btn-sm">Explore</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-cogs fa-3x text-secondary mb-3"></i>
                    <h5>Engineering</h5>
                    <p class="text-muted">108 Opportunities</p>
                    <button class="btn btn-outline-secondary btn-sm">Explore</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Career Counseling CTA -->
    <div class="row mt-5 mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-tie fa-3x mb-3"></i>
                    <h3 class="mb-3">Need Help Finding the Right Opportunity?</h3>
                    <p class="mb-4">Our career counselors can guide you through the job search process and help you find the perfect fit</p>
                    <div>
                        <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-light btn-lg me-2">
                            <i class="fas fa-calendar-plus me-2"></i>Book a Session
                        </a>
                        <a href="<?= APP_URL ?>/student/job-search" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-search me-2"></i>Advanced Search
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
