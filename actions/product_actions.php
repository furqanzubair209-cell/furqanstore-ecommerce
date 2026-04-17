<?php
// ============================================
// ACTIONS: actions/product_actions.php
// ============================================
require_once '../config/db.php';

if (!isLoggedIn()) {
    redirect('auth/login.php');
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$product_id = intval($_POST['product_id'] ?? $_GET['product_id'] ?? 0);

$conn = getConnection();

if ($action === 'delete') {
    // Check permission: admin or product owner (vendor)
    if (isRole('super_admin') || isRole('admin')) {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        showMessage('Product deleted successfully');
    } elseif (isRole('vendor')) {
        $vendor_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND vendor_id = ?");
        $stmt->bind_param("ii", $product_id, $vendor_id);
        $stmt->execute();
        showMessage('Product deleted successfully');
    } else {
        showMessage('Unauthorized', 'error');
    }
}

// Redirect back
$referer = $_SERVER['HTTP_REFERER'] ?? '../index.php';
header("Location: $referer");
exit();
?>
