<?php ob_start(); ?>

<div class="container py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="h3 mb-2"><i class="fas fa-calendar-alt me-2"></i>Manage Weekly Availability</h1>
            <p class="text-muted">Update your recurring weekly hours so students know when you are typically available.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0"><i class="fas fa-business-time me-2 text-primary"></i>Weekly Schedule</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= APP_URL ?>/counselor/schedule" class="row g-3">
                        <?php
                        $days = [
                            'monday' => 'Monday',
                            'tuesday' => 'Tuesday',
                            'wednesday' => 'Wednesday',
                            'thursday' => 'Thursday',
                            'friday' => 'Friday',
                            'saturday' => 'Saturday',
                            'sunday' => 'Sunday'
                        ];
                        ?>
                        <?php foreach ($days as $key => $label): ?>
                            <?php
                            $dayData = $schedule[$key] ?? ['start' => '09:00', 'end' => '17:00', 'available' => false];
                            $isAvailable = !empty($dayData['available']);
                            ?>
                            <div class="col-12">
                                <div class="border rounded-3 p-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                                        <h3 class="h6 mb-0"><i class="fas fa-calendar-day me-2 text-secondary"></i><?= $label ?></h3>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="available-<?= $key ?>" name="schedule[<?= $key ?>][available]" value="1" <?= $isAvailable ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="available-<?= $key ?>">Available</label>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-end">
                                        <div class="col-sm-6 col-md-4">
                                            <label class="form-label" for="start-<?= $key ?>">Start Time</label>
                                            <input type="time" class="form-control" id="start-<?= $key ?>" name="schedule[<?= $key ?>][start]" value="<?= htmlspecialchars($dayData['start']) ?>">
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <label class="form-label" for="end-<?= $key ?>">End Time</label>
                                            <input type="time" class="form-control" id="end-<?= $key ?>" name="schedule[<?= $key ?>][end]" value="<?= htmlspecialchars($dayData['end']) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Schedule</button>
                            <a href="<?= APP_URL ?>/counselor/dashboard" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h2 class="h6 mb-0"><i class="fas fa-lightbulb me-2 text-warning"></i>Tips</h2>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Toggle off any days when you are not available.</li>
                        <li class="mb-2"><i class="fas fa-clock text-primary me-2"></i>Use the start/end fields to set your working window.</li>
                        <li class="mb-2"><i class="fas fa-calendar-plus text-info me-2"></i>Students can still book specific appointments within these hours.</li>
                        <li><i class="fas fa-info-circle text-secondary me-2"></i>Changes take effect immediately once saved.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
