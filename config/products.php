<?php
// api/products.php
require_once '../config/db.php';

// Handle different request methods
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Get products with optional filters
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];
        $types = "";
        
        if (!empty($category) && $category != 'all') {
            $sql .= " AND category = ?";
            $params[] = $category;
            $types .= "s";
        }
        
        if (!empty($search)) {
            $sql .= " AND name LIKE ?";
            $params[] = "%$search%";
            $types .= "s";
        }
        
        // Get total count
        $countSql = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);
        $stmt = $conn->prepare($countSql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $totalResult = $stmt->get_result();
        $total = $totalResult->fetch_assoc()['total'];
        
        // Get paginated results
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        jsonResponse(true, 'Products retrieved successfully', [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'totalPages' => ceil($total / $limit)
        ]);
        break;
        
    case 'POST':
        // Add new product (admin only)
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            jsonResponse(false, 'Unauthorized');
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $sql = "INSERT INTO products (name, price, category, image, badge, description, stock) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssssi", 
            $data['name'], 
            $data['price'], 
            $data['category'], 
            $data['image'], 
            $data['badge'], 
            $data['description'], 
            $data['stock']
        );
        
        if ($stmt->execute()) {
            jsonResponse(true, 'Product added successfully', ['id' => $conn->insert_id]);
        } else {
            jsonResponse(false, 'Failed to add product');
        }
        break;
}
?>
