<?php
// ============================================
// ACTIONS: actions/user_actions.php
// ============================================
require_once '../config/db.php';

if (!isLoggedIn() || (!isRole('super_admin') && !isRole('admin'))) {
    redirect('auth/login.php');
}

$action = $_POST['action'] ?? '';
$user_id = intval($_POST['user_id'] ?? 0);

$conn = getConnection();

if ($action === 'approve') {
    $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    showMessage('User approved successfully');
} elseif ($action === 'suspend') {
    $stmt = $conn->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    showMessage('User suspended successfully');
}

$referer = $_SERVER['HTTP_REFERER'] ?? '../admin/manage_users.php';
header("Location: $referer");
exit();
?>