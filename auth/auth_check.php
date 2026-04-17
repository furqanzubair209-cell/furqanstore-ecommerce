<?php
// Include this at the top of protected pages (admin, vendor)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Optional: restrict by role
function requireRole($role) {
    if ($_SESSION['role'] !== $role && $_SESSION['role'] !== 'super_admin') {
        header('Location: ../auth/login.php');
        exit();
    }
}
?>
