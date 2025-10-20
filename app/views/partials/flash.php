<!-- Flash Messages Partial -->
<?php
$flashTypes = ['success', 'error', 'warning', 'info'];
foreach ($flashTypes as $type):
    if (isset($_SESSION['flash'][$type])):
        $alertClass = [
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'warning' => 'alert-warning',
            'info' => 'alert-info'
        ];
?>
<div class="alert <?= $alertClass[$type] ?> alert-dismissible fade show" role="alert">
    <i class="fas fa-<?= $type === 'error' ? 'exclamation-circle' : ($type === 'success' ? 'check-circle' : 'info-circle') ?> me-2"></i>
    <?= $_SESSION['flash'][$type] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php
        unset($_SESSION['flash'][$type]);
    endif;
endforeach;
?>
