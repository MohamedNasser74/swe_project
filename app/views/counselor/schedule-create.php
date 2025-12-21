<?php ob_start(); ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">Create Schedule</h1>
            <p class="text-muted mb-0">Set your availability for counseling sessions</p>
        </div>
        <a href="<?= APP_URL ?>/counselor/schedule" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Schedule
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="<?= APP_URL ?>/counselor/schedule">
                <div class="row g-4">
                    <?php 
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    foreach ($days as $day): 
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><?= $day ?></h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           name="<?= strtolower($day) ?>_available" id="<?= strtolower($day) ?>_available" checked>
                                    <label class="form-check-label" for="<?= strtolower($day) ?>_available">Available</label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small">Start Time</label>
                                        <input type="time" class="form-control" name="<?= strtolower($day) ?>_start" value="09:00">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">End Time</label>
                                        <input type="time" class="form-control" name="<?= strtolower($day) ?>_end" value="17:00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Save Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
