<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?></h1>
            <p class="text-muted mb-0">Schedule a session with one of our expert counselors</p>
        </div>
        <a href="<?= APP_URL ?>/student/appointments" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Appointments
        </a>
    </div>

    <!-- Booking Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Schedule Your Session
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <div><?= htmlspecialchars($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= APP_URL ?>/student/book-appointment">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="counselor_id" class="form-label">Select Counselor</label>
                                <select class="form-select" id="counselor_id" name="counselor_id" required>
                                    <option value="">Choose a counselor...</option>
                                    <?php if (!empty($counselors)): ?>
                                        <?php foreach ($counselors as $counselor): ?>
                                            <option value="<?= $counselor->id ?>" <?= (isset($form_data['counselor_id']) && $form_data['counselor_id'] == $counselor->id) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($counselor->first_name . ' ' . $counselor->last_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No counselors available</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="session_type" class="form-label">Session Type</label>
                                <select class="form-select" id="session_type" name="session_type" required>
                                    <option value="">Select session type...</option>
                                    <option value="career_guidance" <?= (isset($form_data['session_type']) && $form_data['session_type'] == 'career_guidance') ? 'selected' : '' ?>>
                                        Career Guidance
                                    </option>
                                    <option value="interview_prep" <?= (isset($form_data['session_type']) && $form_data['session_type'] == 'interview_prep') ? 'selected' : '' ?>>
                                        Interview Preparation
                                    </option>
                                    <option value="job_search" <?= (isset($form_data['session_type']) && $form_data['session_type'] == 'job_search') ? 'selected' : '' ?>>
                                        Job Search Strategy
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="appointment_date" class="form-label">Preferred Date</label>
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                                       value="<?= htmlspecialchars($form_data['appointment_date'] ?? '') ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="appointment_time" class="form-label">Preferred Time</label>
                                <select class="form-select" id="appointment_time" name="appointment_time" required>
                                    <option value="">Select time...</option>
                                    <option value="09:00" <?= (isset($form_data['appointment_time']) && $form_data['appointment_time'] == '09:00') ? 'selected' : '' ?>>9:00 AM</option>
                                    <option value="10:00" <?= (isset($form_data['appointment_time']) && $form_data['appointment_time'] == '10:00') ? 'selected' : '' ?>>10:00 AM</option>
                                    <option value="11:00" <?= (isset($form_data['appointment_time']) && $form_data['appointment_time'] == '11:00') ? 'selected' : '' ?>>11:00 AM</option>
                                    <option value="12:00" <?= (isset($form_data['appointment_time']) && $form_data['appointment_time'] == '12:00') ? 'selected' : '' ?>>12:00 PM</option>
                                    <option value="13:00" <?= (isset($form_data['appointment_time']) && $form_data['appointment_time'] == '13:00') ? 'selected' : '' ?>>1:00 PM</option>
                                    <option value="14:00" <?= (isset($form_data['appointment_time']) && $form_data['appointment_time'] == '14:00') ? 'selected' : '' ?>>2:00 PM</option>
                                    <option value="15:00" <?= (isset($form_data['appointment_time']) && $form_data['appointment_time'] == '15:00') ? 'selected' : '' ?>>3:00 PM</option>
                                    <option value="16:00" <?= (isset($form_data['appointment_time']) && $form_data['appointment_time'] == '16:00') ? 'selected' : '' ?>>4:00 PM</option>
                                    <option value="17:00" <?= (isset($form_data['appointment_time']) && $form_data['appointment_time'] == '17:00') ? 'selected' : '' ?>>5:00 PM</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Additional Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" 
                                      placeholder="Tell us about your career goals, specific questions, or any other information that might help the counselor prepare for your session..."><?= htmlspecialchars($form_data['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= APP_URL ?>/student/dashboard" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Book Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Available Counselors Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Our Counselors
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($counselors)): ?>
                        <?php foreach (array_slice($counselors, 0, 3) as $counselor): ?>
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($counselor->first_name . ' ' . $counselor->last_name) ?></h6>
                                    <small class="text-muted">Career Counselor</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No counselors available at the moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Session Types Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Session Types
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Career Guidance</h6>
                        <small class="text-muted">General career planning and advice</small>
                    </div>
                    <div class="mb-3">
                    </div>
                    <div class="mb-3">
                        <h6 class="text-info">Interview Preparation</h6>
                        <small class="text-muted">Mock interviews and tips for success</small>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-warning">Job Search Strategy</h6>
                        <small class="text-muted">Finding opportunities and application strategies</small>
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