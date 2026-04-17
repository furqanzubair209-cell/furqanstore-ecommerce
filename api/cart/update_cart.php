<?php
// ============================================
// API: api/cart/update_cart.php
// ============================================
require_once '../../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? 0;
$quantity = intval($data['quantity'] ?? 0);
$user_id = $_SESSION['user_id'];

$conn = getConnection();

if ($quantity <= 0) {
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    $stmt->execute();
}

echo json_encode(['success' => true]);
?>