<?php ob_start(); ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?></h1>
            <p class="text-muted mb-0">Master your interview skills and land your dream job</p>
        </div>
        <div>
            <a href="<?= APP_URL ?>/student/dashboard" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Introduction Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-body text-center py-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-handshake fa-2x"></i>
                    </div>
                    <h4 class="mb-3">Ace Your Next Interview</h4>
                    <p class="text-muted mb-0">Prepare yourself with these essential tips and best practices for successful interviews</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Interview Tips Content -->
    <div class="row g-4">
        <!-- Before the Interview -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>Before the Interview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-primary">
                            <i class="fas fa-search me-2"></i>Research the Company
                        </h6>
                        <ul class="text-muted">
                            <li>Study the company's website, mission, and values</li>
                            <li>Research recent news and achievements</li>
                            <li>Understand their products, services, and competitors</li>
                            <li>Check the company culture and work environment</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary">
                            <i class="fas fa-user-tie me-2"></i>Prepare Your Documents
                        </h6>
                        <ul class="text-muted">
                            <li>Print multiple copies of your resume</li>
                            <li>Prepare a portfolio of your work (if applicable)</li>
                            <li>Bring a list of references</li>
                            <li>Have a pen and notepad ready</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary">
                            <i class="fas fa-comments me-2"></i>Practice Common Questions
                        </h6>
                        <ul class="text-muted">
                            <li>"Tell me about yourself"</li>
                            <li>"Why do you want to work here?"</li>
                            <li>"What are your strengths and weaknesses?"</li>
                            <li>"Where do you see yourself in 5 years?"</li>
                        </ul>
                    </div>

                    <div>
                        <h6 class="text-primary">
                            <i class="fas fa-tshirt me-2"></i>Dress Appropriately
                        </h6>
                        <ul class="text-muted">
                            <li>Choose professional attire based on company culture</li>
                            <li>Ensure clothes are clean and wrinkle-free</li>
                            <li>Keep accessories minimal and professional</li>
                            <li>Pay attention to grooming and hygiene</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- During the Interview -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-comments-dollar me-2"></i>During the Interview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-success">
                            <i class="fas fa-smile me-2"></i>Make a Great First Impression
                        </h6>
                        <ul class="text-muted">
                            <li>Arrive 10-15 minutes early</li>
                            <li>Greet everyone with a firm handshake</li>
                            <li>Make eye contact and smile</li>
                            <li>Show enthusiasm and positive energy</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-success">
                            <i class="fas fa-language me-2"></i>Communication Skills
                        </h6>
                        <ul class="text-muted">
                            <li>Speak clearly and confidently</li>
                            <li>Listen actively to the interviewer</li>
                            <li>Use the STAR method for behavioral questions</li>
                            <li>Ask for clarification if needed</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-success">
                            <i class="fas fa-chart-line me-2"></i>Showcase Your Skills
                        </h6>
                        <ul class="text-muted">
                            <li>Provide specific examples of your achievements</li>
                            <li>Highlight relevant skills and experiences</li>
                            <li>Demonstrate your problem-solving abilities</li>
                            <li>Show how you can add value to the company</li>
                        </ul>
                    </div>

                    <div>
                        <h6 class="text-success">
                            <i class="fas fa-question-circle me-2"></i>Ask Thoughtful Questions
                        </h6>
                        <ul class="text-muted">
                            <li>"What does a typical day look like in this role?"</li>
                            <li>"What are the biggest challenges facing the team?"</li>
                            <li>"What opportunities are there for growth?"</li>
                            <li>"What are the next steps in the interview process?"</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- After the Interview -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>After the Interview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-info">
                            <i class="fas fa-envelope me-2"></i>Follow Up
                        </h6>
                        <ul class="text-muted">
                            <li>Send a thank-you email within 24 hours</li>
                            <li>Personalize your message for each interviewer</li>
                            <li>Reiterate your interest in the position</li>
                            <li>Mention something specific from your conversation</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-info">
                            <i class="fas fa-lightbulb me-2"></i>Reflect on Your Performance
                        </h6>
                        <ul class="text-muted">
                            <li>Note questions you found challenging</li>
                            <li>Identify areas for improvement</li>
                            <li>Consider what went well</li>
                            <li>Prepare better for future interviews</li>
                        </ul>
                    </div>

                    <div>
                        <h6 class="text-info">
                            <i class="fas fa-clock me-2"></i>Be Patient
                        </h6>
                        <ul class="text-muted">
                            <li>Wait for the specified timeline before following up</li>
                            <li>Continue your job search in the meantime</li>
                            <li>Stay positive and professional</li>
                            <li>Learn from each interview experience</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Common Mistakes to Avoid -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Common Mistakes to Avoid
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-danger">
                            <i class="fas fa-times me-2"></i>Don't Do These
                        </h6>
                        <ul class="text-muted">
                            <li><strong>Arriving Late:</strong> Always plan to arrive early</li>
                            <li><strong>Being Unprepared:</strong> Research and practice beforehand</li>
                            <li><strong>Badmouthing Previous Employers:</strong> Stay positive</li>
                            <li><strong>Lying or Exaggerating:</strong> Be honest about your skills</li>
                            <li><strong>Checking Your Phone:</strong> Keep it silent and away</li>
                            <li><strong>Talking Too Much or Too Little:</strong> Find the right balance</li>
                            <li><strong>Focusing Only on Salary:</strong> Show interest in the role</li>
                            <li><strong>Not Asking Questions:</strong> Show your curiosity</li>
                            <li><strong>Poor Body Language:</strong> Sit up straight, make eye contact</li>
                            <li><strong>Forgetting Names:</strong> Write them down if needed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STAR Method Explanation -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>The STAR Method for Behavioral Questions
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-4">Use this structured approach to answer behavioral interview questions effectively:</p>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <strong>S</strong>
                                </div>
                                <h6 class="text-primary">Situation</h6>
                                <p class="text-muted small mb-0">Set the context and background</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <strong>T</strong>
                                </div>
                                <h6 class="text-success">Task</h6>
                                <p class="text-muted small mb-0">Describe your responsibility</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <strong>A</strong>
                                </div>
                                <h6 class="text-info">Action</h6>
                                <p class="text-muted small mb-0">Explain what you did</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <strong>R</strong>
                                </div>
                                <h6 class="text-warning">Result</h6>
                                <p class="text-muted small mb-0">Share the outcome achieved</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Book a Session CTA -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body text-center py-4">
                    <h4 class="mb-3">Need Personalized Interview Coaching?</h4>
                    <p class="mb-4">Our expert counselors can help you prepare for your specific interview scenario</p>
                    <a href="<?= APP_URL ?>/student/book-appointment" class="btn btn-light btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Book a Session
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
