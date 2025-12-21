<?php ob_start(); ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">Edit Schedule</h1>
            <p class="text-muted mb-0">Update your availability for counseling sessions</p>
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
                    $scheduleData = $schedule ?? [];
                    foreach ($days as $day): 
                        $dayKey = strtolower($day);
                        $daySchedule = $scheduleData[$dayKey] ?? ['start' => '09:00', 'end' => '17:00', 'available' => true];
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><?= $day ?></h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           name="<?= $dayKey ?>_available" id="<?= $dayKey ?>_available" 
                                           <?= $daySchedule['available'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="<?= $dayKey ?>_available">Available</label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small">Start Time</label>
                                        <input type="time" class="form-control" name="<?= $dayKey ?>_start" 
                                               value="<?= $daySchedule['start'] ?>">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">End Time</label>
                                        <input type="time" class="form-control" name="<?= $dayKey ?>_end" 
                                               value="<?= $daySchedule['end'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Update Schedule
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
