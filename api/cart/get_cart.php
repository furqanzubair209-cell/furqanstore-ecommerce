<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => true, 'data' => ['items' => []]]);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'furqanstore_db');
$user_id = $_SESSION['user_id'];

$sql = "SELECT ci.*, p.name, p.price, p.image_url 
        FROM cart_items ci 
        JOIN products p ON ci.product_id = p.id 
        WHERE ci.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'data' => ['items' => $items]]);
?>