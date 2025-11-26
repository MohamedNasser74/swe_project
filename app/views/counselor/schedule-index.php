<?php ob_start(); ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h2"><i class="fas fa-calendar-alt"></i> Manage Schedule</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= APP_URL ?>/counselor/scheduleCreate" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Time Slot
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title text-muted"><i class="fas fa-calendar-check text-primary"></i> Total Slots</h5>
                    <h2 class="card-text text-primary"><?= $stats['total'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title text-muted"><i class="fas fa-check-circle text-success"></i> Available</h5>
                    <h2 class="card-text text-success"><?= $stats['available'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title text-muted"><i class="fas fa-user-check text-info"></i> Booked</h5>
                    <h2 class="card-text text-info"><?= $stats['booked'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title text-muted"><i class="fas fa-times-circle text-danger"></i> Unavailable</h5>
                    <h2 class="card-text text-danger"><?= $stats['unavailable'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Your Schedule Slots</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($schedules)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedules as $slot): ?>
                                <tr>
                                    <td><strong><?= date('M d, Y', strtotime($slot->schedule_date)) ?></strong></td>
                                    <td>
                                        <?= date('h:i A', strtotime($slot->start_time)) ?> -
                                        <?= date('h:i A', strtotime($slot->end_time)) ?>
                                    </td>
                                    <td>
                                        <?php
                                        $start = new DateTime($slot->start_time);
                                        $end = new DateTime($slot->end_time);
                                        $duration = $start->diff($end);
                                        echo $duration->h . 'h ' . $duration->i . 'm';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClasses = [
                                            'available' => 'success',
                                            'booked' => 'info',
                                            'unavailable' => 'secondary'
                                        ];
                                        $badgeClass = $statusClasses[$slot->status] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?>"><?= ucfirst($slot->status) ?></span>
                                    </td>
                                    <td><?= !empty($slot->notes) ? htmlspecialchars(mb_strimwidth($slot->notes, 0, 30, '...')) : '-' ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a class="btn btn-sm btn-warning" href="<?= APP_URL ?>/counselor/scheduleEdit/<?= $slot->id ?>" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?= APP_URL ?>/counselor/scheduleDelete/<?= $slot->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this slot?');">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No schedule slots found. <a href="<?= APP_URL ?>/counselor/scheduleCreate" class="alert-link">Create one now</a>.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
