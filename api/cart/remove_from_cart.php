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
    echo json_encode(['success' => false]);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$product_id = $input['product_id'] ?? 0;
$user_id = $_SESSION['user_id'];

$conn = new mysqli('localhost', 'root', '', 'furqanstore_db');
$stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();

echo json_encode(['success' => true]);
?>