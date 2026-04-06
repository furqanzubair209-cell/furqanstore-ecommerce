<?php
// api/orders.php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    jsonResponse(false, 'Please login first');
}

$method = $_SERVER['REQUEST_METHOD'];
$userId = $_SESSION['user_id'];

switch($method) {
    case 'GET':
        // Get user's orders
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while ($order = $result->fetch_assoc()) {
            // Get order items
            $itemsSql = "SELECT oi.*, p.name, p.image 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?";
            $itemsStmt = $conn->prepare($itemsSql);
            $itemsStmt->bind_param("i", $order['id']);
            $itemsStmt->execute();
            $itemsResult = $itemsStmt->get_result();
            
            $items = [];
            while ($item = $itemsResult->fetch_assoc()) {
                $items[] = $item;
            }
            
            $order['items'] = $items;
            $orders[] = $order;
        }
        
        jsonResponse(true, 'Orders retrieved', $orders);
        break;
        
    case 'POST':
        // Create new order
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Get cart items
        $cartSql = "SELECT c.*, p.price FROM cart c 
                   JOIN products p ON c.product_id = p.id 
                   WHERE c.user_id = ?";
        $stmt = $conn->prepare($cartSql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $cartResult = $stmt->get_result();
        
        if ($cartResult->num_rows === 0) {
            jsonResponse(false, 'Cart is empty');
        }
        
        $total = 0;
        $cartItems = [];
        while ($item = $cartResult->fetch_assoc()) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            $cartItems[] = $item;
        }
        
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Create order
            $orderSql = "INSERT INTO orders (user_id, total_amount, shipping_address, phone) 
                        VALUES (?, ?, ?, ?)";
            $orderStmt = $conn->prepare($orderSql);
            $orderStmt->bind_param("idss", $userId, $total, $data['address'], $data['phone']);
            $orderStmt->execute();
            $orderId = $conn->insert_id;
            
            // Add order items
            $itemSql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $itemStmt = $conn->prepare($itemSql);
            
            foreach ($cartItems as $item) {
                $itemStmt->bind_param("iiid", $orderId, $item['product_id'], $item['quantity'], $item['price']);
                $itemStmt->execute();
            }
            
            // Clear cart
            $clearSql = "DELETE FROM cart WHERE user_id = ?";
            $clearStmt = $conn->prepare($clearSql);
            $clearStmt->bind_param("i", $userId);
            $clearStmt->execute();
            
            // Commit transaction
            $conn->commit();
            
            jsonResponse(true, 'Order placed successfully', ['order_id' => $orderId]);
            
        } catch (Exception $e) {
            $conn->rollback();
            jsonResponse(false, 'Failed to place order: ' . $e->getMessage());
        }
        break;
}
?>