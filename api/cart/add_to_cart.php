<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'furqanstore_db');
$input = json_decode(file_get_contents('php://input'), true);
$product_id = $input['product_id'] ?? 0;
$user_id = $_SESSION['user_id'];

// Check if product exists
$checkProduct = $conn->prepare("SELECT id FROM products WHERE id = ? AND status = 'active'");
$checkProduct->bind_param("i", $product_id);
$checkProduct->execute();
if ($checkProduct->get_result()->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}

// Check if already in cart
$check = $conn->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
$existing = $check->get_result()->fetch_assoc();

if ($existing) {
    $newQty = $existing['quantity'] + 1;
    $update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $update->bind_param("ii", $newQty, $existing['id']);
    $update->execute();
} else {
    $insert = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $insert->bind_param("ii", $user_id, $product_id);
    $insert->execute();
}

echo json_encode(['success' => true, 'message' => 'Added to cart']);
?>
