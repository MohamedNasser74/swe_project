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

$resourceId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Placeholder download handler; counts will be stored once persistence is connected.
$_SESSION['resources_notice'] = $resourceId > 0
    ? "Download simulated for resource #{$resourceId}. Attach storage to serve files."
    : 'Download simulated. Attach storage to serve files.';

header('Location: resources.php');
exit;
