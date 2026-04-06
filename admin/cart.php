<?php
// api/cart.php
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    jsonResponse(false, 'Please login first');
}

$method = $_SERVER['REQUEST_METHOD'];
$userId = $_SESSION['user_id'];

switch($method) {
    case 'GET':
        // Get user's cart
        $sql = "SELECT c.id, c.product_id, c.quantity, p.name, p.price, p.image 
                FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cart = [];
        $total = 0;
        while ($row = $result->fetch_assoc()) {
            $row['subtotal'] = $row['price'] * $row['quantity'];
            $total += $row['subtotal'];
            $cart[] = $row;
        }
        
        jsonResponse(true, 'Cart retrieved', [
            'items' => $cart,
            'total' => $total,
            'total_items' => count($cart)
        ]);
        break;
        
    case 'POST':
        // Add to cart
        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'];
        $quantity = isset($data['quantity']) ? $data['quantity'] : 1;
        
        // Check if item already in cart
        $checkSql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update quantity
            $cartItem = $result->fetch_assoc();
            $newQuantity = $cartItem['quantity'] + $quantity;
            $updateSql = "UPDATE cart SET quantity = ? WHERE id = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("ii", $newQuantity, $cartItem['id']);
        } else {
            // Insert new item
            $insertSql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("iii", $userId, $productId, $quantity);
        }
        
        if ($stmt->execute()) {
            jsonResponse(true, 'Added to cart');
        } else {
            jsonResponse(false, 'Failed to add to cart');
        }
        break;
        
    case 'PUT':
        // Update quantity
        $data = json_decode(file_get_contents('php://input'), true);
        $cartId = $data['cart_id'];
        $quantity = $data['quantity'];
        
        if ($quantity <= 0) {
            // Delete item
            $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $cartId, $userId);
        } else {
            // Update quantity
            $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $quantity, $cartId, $userId);
        }
        
        if ($stmt->execute()) {
            jsonResponse(true, 'Cart updated');
        } else {
            jsonResponse(false, 'Update failed');
        }
        break;
        
    case 'DELETE':
        // Remove from cart
        $cartId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cartId, $userId);
        
        if ($stmt->execute()) {
            jsonResponse(true, 'Item removed from cart');
        } else {
            jsonResponse(false, 'Failed to remove item');
        }
        break;
}
?>