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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: resources.php');
    exit;
}

// Placeholder handler while database integration is pending.
$_SESSION['resources_notice'] = 'Resource upload simulated. Connect storage and database to persist.';

header('Location: resources.php');
exit;
