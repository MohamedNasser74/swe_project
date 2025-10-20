<?php ob_start(); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold"><?= $page_title ?></h2>
                        <p class="text-muted">Join our community and start your career journey today!</p>
                    </div>

                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <div><?= htmlspecialchars($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= APP_URL ?>/auth/register">
                        <input type="hidden" name="csrf_token" value="<?= FormHelper::csrfToken() ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?= htmlspecialchars($form_data['first_name'] ?? '') ?>" 
                                       placeholder="First name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?= htmlspecialchars($form_data['last_name'] ?? '') ?>" 
                                       placeholder="Last name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="username" name="username" 
                        value="<?= htmlspecialchars($form_data['username'] ?? '') ?>" 
                        placeholder="Choose a username" required pattern="^[A-Za-z0-9]{3,}$" title="At least 3 characters, letters and numbers only (no spaces or symbols)">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" 
                        value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" 
                        placeholder="Enter your email" required pattern="^[^@\s]+@(gmail\.com|icloud\.com|yahoo\.com|outlook\.com|hotmail\.com)$" title="Use gmail.com, icloud.com, yahoo.com, outlook.com, or hotmail.com">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?= htmlspecialchars($form_data['phone'] ?? '') ?>" 
                                       placeholder="Your phone number">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">I am a...</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select your role</option>
                                <option value="student" <?= ($form_data['role'] ?? '') === 'student' ? 'selected' : '' ?>>
                                    Student - Seeking career guidance
                                </option>
                                <option value="counselor" <?= ($form_data['role'] ?? '') === 'counselor' ? 'selected' : '' ?>>
                                    Career Counselor - Want to help students
                                </option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                     <input type="password" class="form-control" id="password" name="password" 
                         placeholder="Create a password" required pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$" title="Min 8 chars with uppercase, lowercase, number, and special character">
                                </div>
                    <small class="text-muted">Min 8 chars with uppercase, lowercase, number, and special character</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           placeholder="Confirm your password" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-primary">Terms of Service</a> and 
                                <a href="#" class="text-primary">Privacy Policy</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted">Already have an account? 
                    <a href="<?= APP_URL ?>/auth/login" class="text-primary text-decoration-none fw-bold">Sign in here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>