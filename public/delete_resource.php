<?php
session_start();

require_once __DIR__ . '/../app/config/config.php';

if (!isset($_SESSION['counselor_id'])) {
    if (isset($_SESSION['user_role'], $_SESSION['user_id']) && $_SESSION['user_role'] === 'counselor') {
        $_SESSION['counselor_id'] = (int) $_SESSION['user_id'];
    } else {
        header('Location: ' . APP_URL . '/auth/login');
        exit;
    }
}

$resourceId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

// Placeholder delete handler until database integration is available.
$_SESSION['resources_notice'] = $resourceId > 0
    ? "Resource #{$resourceId} marked for deletion (simulation)."
    : 'Resource removal simulated. Connect database to persist.';

header('Location: resources.php');
exit;
