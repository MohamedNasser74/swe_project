<?php ob_start(); ?>

<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold"><?= $page_title ?></h1>
        <p class="lead text-muted">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    </div>

    <div class="row g-5">
        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h3 class="mb-4"><i class="fas fa-paper-plane text-primary me-2"></i>Send us a Message</h3>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= APP_URL ?>/home/contact">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Your Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($form_data['name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" required>
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       value="<?= htmlspecialchars($form_data['subject'] ?? '') ?>" required>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required><?= htmlspecialchars($form_data['message'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4"><i class="fas fa-info-circle text-primary me-2"></i>Contact Information</h4>
                    
                    <div class="d-flex mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Address</h6>
                            <p class="text-muted mb-0">123 Career Street, Suite 456<br>Education City, EC 12345</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-envelope text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Email</h6>
                            <p class="text-muted mb-0">
                                <a href="mailto:support@careerplatform.com" class="text-decoration-none">support@careerplatform.com</a>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-phone text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Phone</h6>
                            <p class="text-muted mb-0">+1 (555) 123-4567</p>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-clock text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Business Hours</h6>
                            <p class="text-muted mb-0">Mon - Fri: 9:00 AM - 6:00 PM<br>Sat: 10:00 AM - 2:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Links -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3">Follow Us</h5>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fab fa-linkedin-in"></i>
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
