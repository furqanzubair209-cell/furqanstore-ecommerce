<?php
// ============================================
// ACTIONS: actions/order_actions.php
// ============================================
require_once '../config/db.php';

if (!isLoggedIn()) {
    redirect('auth/login.php');
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$order_id = intval($_POST['order_id'] ?? $_GET['order_id'] ?? 0);

$conn = getConnection();

if ($action === 'update_status') {
    $new_status = $_POST['status'] ?? 'delivered';
    
    // Authorization check
    if (isRole('super_admin') || isRole('admin')) {
        // Admins can update any order
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
    } elseif (isRole('vendor')) {
        // Vendors can only update orders that contain THEIR products
        // We verify that the order_id exists in order_items for this vendor
        $vendor_id = $_SESSION['user_id'];
        $check = $conn->prepare("SELECT 1 FROM order_items WHERE order_id = ? AND vendor_id = ? LIMIT 1");
        $check->bind_param("ii", $order_id, $vendor_id);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $new_status, $order_id);
        } else {
            die("Unauthorized to update this order.");
        }
    } else {
        die("Unauthorized role.");
    }
    
    if (isset($stmt) && $stmt->execute()) {
        // Successfully updated
    }
}

// Redirect back to the previous page
$referer = $_SERVER['HTTP_REFERER'] ?? '../index.php';
header("Location: $referer");
exit();
?>
