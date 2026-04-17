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

$input = json_decode(file_get_contents('php://input'), true);
$shipping_address = $input['shipping_address'] ?? '';
$payment_method = $input['payment_method'] ?? 'cod';
$user_id = $_SESSION['user_id'];

$conn = new mysqli('localhost', 'root', '', 'furqanstore_db');

// Get cart items
$cartQuery = $conn->prepare("
    SELECT ci.*, p.price, p.vendor_id 
    FROM cart_items ci 
    JOIN products p ON ci.product_id = p.id 
    WHERE ci.user_id = ?
");
$cartQuery->bind_param("i", $user_id);
$cartQuery->execute();
$cartItems = $cartQuery->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($cartItems)) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit();
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Create order
$status = ($payment_method === 'cod') ? 'pending' : 'processing';
$orderStmt = $conn->prepare("INSERT INTO orders (user_id, total, status, payment_method, shipping_address) VALUES (?, ?, ?, ?, ?)");
$orderStmt->bind_param("idsss", $user_id, $total, $status, $payment_method, $shipping_address);
$orderStmt->execute();
$order_id = $conn->insert_id;

// Create order items
foreach ($cartItems as $item) {
    $commission = 10;
    $vendor_earning = $item['price'] * $item['quantity'] * (1 - $commission/100);
    
    $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, vendor_id, quantity, price, vendor_earning) VALUES (?, ?, ?, ?, ?, ?)");
    $itemStmt->bind_param("iiiiid", $order_id, $item['product_id'], $item['vendor_id'], $item['quantity'], $item['price'], $vendor_earning);
    $itemStmt->execute();
}

// Clear cart
$clearStmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
$clearStmt->bind_param("i", $user_id);
$clearStmt->execute();

echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
?>
