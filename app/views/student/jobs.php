<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?></h1>
            <p class="text-muted mb-0">Browse and search for career opportunities</p>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#jobAlertModal">
                <i class="fas fa-bell me-2"></i>Set Job Alert
            </button>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="<?= APP_URL ?>/student/jobs">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="keyword" class="form-label">Keywords</label>
                                <input type="text" class="form-control" id="keyword" name="keyword" 
                                       placeholder="Job title, skills, company..." 
                                       value="<?= $_GET['keyword'] ?? '' ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       placeholder="City, state, remote..." 
                                       value="<?= $_GET['location'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="job_type" class="form-label">Job Type</label>
                                <select class="form-select" id="job_type" name="job_type">
                                    <option value="">All Types</option>
                                    <option value="full-time" <?= ($_GET['job_type'] ?? '') === 'full-time' ? 'selected' : '' ?>>Full-time</option>
                                    <option value="part-time" <?= ($_GET['job_type'] ?? '') === 'part-time' ? 'selected' : '' ?>>Part-time</option>
                                    <option value="contract" <?= ($_GET['job_type'] ?? '') === 'contract' ? 'selected' : '' ?>>Contract</option>
                                    <option value="internship" <?= ($_GET['job_type'] ?? '') === 'internship' ? 'selected' : '' ?>>Internship</option>
                                    <option value="remote" <?= ($_GET['job_type'] ?? '') === 'remote' ? 'selected' : '' ?>>Remote</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="experience" class="form-label">Experience</label>
                                <select class="form-select" id="experience" name="experience">
                                    <option value="">All Levels</option>
                                    <option value="entry" <?= ($_GET['experience'] ?? '') === 'entry' ? 'selected' : '' ?>>Entry Level</option>
                                    <option value="mid" <?= ($_GET['experience'] ?? '') === 'mid' ? 'selected' : '' ?>>Mid Level</option>
                                    <option value="senior" <?= ($_GET['experience'] ?? '') === 'senior' ? 'selected' : '' ?>>Senior Level</option>
                                    <option value="executive" <?= ($_GET['experience'] ?? '') === 'executive' ? 'selected' : '' ?>>Executive</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Listings -->
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Job Opportunities</h5>
                <small class="text-muted">Showing sample job listings</small>
            </div>

            <!-- Sample Job Listings -->
            <?php
            $sampleJobs = [
                [
                    'title' => 'Software Developer',
                    'company' => 'Tech Solutions Inc.',
                    'location' => 'New York, NY',
                    'type' => 'Full-time',
                    'salary' => '$70,000 - $90,000',
                    'posted' => '2 days ago',
                    'description' => 'We are looking for a passionate Software Developer to join our growing team. You will be responsible for developing and maintaining web applications using modern technologies.',
                    'requirements' => ['Bachelor\'s degree in Computer Science', '2+ years experience', 'Proficiency in JavaScript, HTML, CSS', 'Experience with React or Vue.js'],
                    'featured' => true
                ],
                [
                    'title' => 'Marketing Coordinator',
                    'company' => 'Creative Agency Ltd.',
                    'location' => 'Los Angeles, CA',
                    'type' => 'Full-time',
                    'salary' => '$45,000 - $55,000',
                    'posted' => '1 week ago',
                    'description' => 'Join our creative team as a Marketing Coordinator. You will assist in developing marketing campaigns and managing social media presence.',
                    'requirements' => ['Bachelor\'s degree in Marketing', '1+ years experience', 'Strong communication skills', 'Social media expertise'],
                    'featured' => false
                ],
                [
                    'title' => 'Data Analyst Intern',
                    'company' => 'DataCorp Analytics',
                    'location' => 'Remote',
                    'type' => 'Internship',
                    'salary' => '$15 - $20/hour',
                    'posted' => '3 days ago',
                    'description' => 'Great opportunity for students or recent graduates to gain hands-on experience in data analysis and business intelligence.',
                    'requirements' => ['Currently pursuing degree', 'Knowledge of Excel/SQL', 'Analytical mindset', 'Attention to detail'],
                    'featured' => false
                ],
                [
                    'title' => 'Project Manager',
                    'company' => 'Global Consulting Group',
                    'location' => 'Chicago, IL',
                    'type' => 'Full-time',
                    'salary' => '$85,000 - $105,000',
                    'posted' => '5 days ago',
                    'description' => 'We seek an experienced Project Manager to lead cross-functional teams and deliver high-quality projects on time and within budget.',
                    'requirements' => ['PMP certification preferred', '5+ years project management', 'Leadership experience', 'Excellent communication'],
                    'featured' => true
                ],
                [
                    'title' => 'Customer Service Representative',
                    'company' => 'ServiceFirst Solutions',
                    'location' => 'Austin, TX',
                    'type' => 'Part-time',
                    'salary' => '$16 - $20/hour',
                    'posted' => '1 day ago',
                    'description' => 'Provide exceptional customer service via phone, email, and chat. Perfect for students or those seeking flexible schedules.',
                    'requirements' => ['High school diploma', 'Strong communication skills', 'Problem-solving abilities', 'Flexible schedule'],
                    'featured' => false
                ]
            ];

            foreach ($sampleJobs as $index => $job): ?>
                <div class="card mb-3 <?= $job['featured'] ? 'border-warning' : '' ?>">
                    <?php if ($job['featured']): ?>
                        <div class="card-header bg-warning text-dark py-2">
                            <small><i class="fas fa-star me-1"></i>Featured Job</small>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title mb-2">
                                    <a href="#" class="text-decoration-none" data-bs-toggle="collapse" 
                                       data-bs-target="#job<?= $index ?>" aria-expanded="false">
                                        <?= $job['title'] ?>
                                    </a>
                                </h5>
                                <h6 class="card-subtitle mb-2 text-primary"><?= $job['company'] ?></h6>
                                <div class="d-flex flex-wrap gap-3 mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i><?= $job['location'] ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-briefcase me-1"></i><?= $job['type'] ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-dollar-sign me-1"></i><?= $job['salary'] ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i><?= $job['posted'] ?>
                                    </small>
                                </div>
                                <p class="card-text"><?= $job['description'] ?></p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex flex-column gap-2">
                                    <button class="btn btn-primary btn-sm" onclick="applyForJob('<?= $job['title'] ?>', '<?= $job['company'] ?>')">
                                        <i class="fas fa-paper-plane me-1"></i>Apply Now
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="saveJob(<?= $index ?>)">
                                        <i class="fas fa-bookmark me-1"></i>Save Job
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="shareJob('<?= $job['title'] ?>')">
                                        <i class="fas fa-share me-1"></i>Share
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Job Details Collapse -->
                        <div class="collapse mt-3" id="job<?= $index ?>">
                            <div class="border-top pt-3">
                                <h6>Requirements:</h6>
                                <ul>
                                    <?php foreach ($job['requirements'] as $req): ?>
                                        <li><?= $req ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="mt-3">
                                    <button class="btn btn-success me-2" onclick="applyForJob('<?= $job['title'] ?>', '<?= $job['company'] ?>')">
                                        <i class="fas fa-paper-plane me-1"></i>Apply for this Position
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="contactEmployer('<?= $job['company'] ?>')">
                                        <i class="fas fa-envelope me-1"></i>Contact Employer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Pagination -->
            <nav aria-label="Job search pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Career Resources -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Career Resources</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?= APP_URL ?>/student/book-appointment" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-check me-2"></i>Interview Preparation
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-line me-2"></i>Salary Calculator
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-users me-2"></i>Networking Tips
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Searches -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Searches</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark">Software Developer</span>
                        <span class="badge bg-light text-dark">Remote Jobs</span>
                        <span class="badge bg-light text-dark">Entry Level</span>
                        <span class="badge bg-light text-dark">Marketing</span>
                    </div>
                </div>
            </div>

            <!-- Job Alerts -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-bell me-2"></i>Job Alerts</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Get notified when new jobs match your criteria.</p>
                    <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#jobAlertModal">
                        <i class="fas fa-plus me-1"></i>Create Job Alert
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Job Alert Modal -->
<div class="modal fade" id="jobAlertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Job Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="jobAlertForm">
                    <div class="mb-3">
                        <label for="alertKeywords" class="form-label">Keywords</label>
                        <input type="text" class="form-control" id="alertKeywords" placeholder="e.g., Software Developer, Marketing">
                    </div>
                    <div class="mb-3">
                        <label for="alertLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" id="alertLocation" placeholder="e.g., New York, Remote">
                    </div>
                    <div class="mb-3">
                        <label for="alertFrequency" class="form-label">Email Frequency</label>
                        <select class="form-select" id="alertFrequency">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createJobAlert()">Create Alert</button>
            </div>
        </div>
    </div>
</div>

<script>
function applyForJob(title, company) {
    alert(`Application feature coming soon!\n\nJob: ${title}\nCompany: ${company}\n\nFor now, please contact the company directly or work with a career counselor to prepare your application.`);
}

function saveJob(index) {
    alert('Job saved to your favorites! (Feature coming soon)');
}

function shareJob(title) {
    if (navigator.share) {
        navigator.share({
            title: `Job Opportunity: ${title}`,
            text: `Check out this job opportunity: ${title}`,
            url: window.location.href
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('Job link copied to clipboard!');
        });
    }
}

function contactEmployer(company) {
    alert(`Contact feature coming soon!\n\nCompany: ${company}\n\nFor now, please use the company's website or LinkedIn to reach out directly.`);
}

function createJobAlert() {
    const keywords = document.getElementById('alertKeywords').value;
    const location = document.getElementById('alertLocation').value;
    const frequency = document.getElementById('alertFrequency').value;
    
    if (!keywords.trim()) {
        alert('Please enter keywords for your job alert.');
        return;
    }
    
    alert(`Job alert created!\n\nKeywords: ${keywords}\nLocation: ${location || 'Any'}\nFrequency: ${frequency}\n\nYou will receive email notifications when matching jobs are posted.`);
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('jobAlertModal'));
    modal.hide();
    
    // Reset form
    document.getElementById('jobAlertForm').reset();
}
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>