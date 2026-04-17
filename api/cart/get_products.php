<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = new mysqli('localhost', 'root', '', 'furqanstore_db');

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'all';
$page = intval($_GET['page'] ?? 1);
$limit = 12;
$offset = ($page - 1) * $limit;

$sql = "SELECT p.*, u.full_name as vendor_name 
        FROM products p 
        LEFT JOIN users u ON p.vendor_id = u.id 
        WHERE p.status = 'active'";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND p.name LIKE '%$search%'";
}

$countSql = str_replace("SELECT p.*, u.full_name as vendor_name", "SELECT COUNT(*) as total", $sql);
$countResult = $conn->query($countSql);
$total = $countResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

$sql .= " ORDER BY p.id DESC LIMIT $offset, $limit";
$result = $conn->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC);

// Add default image if not set
foreach ($products as &$product) {
    if (empty($product['image_url'])) {
        $product['image_url'] = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400';
    }
}

echo json_encode([
    'success' => true,
    'data' => [
        'products' => $products,
        'total' => $total,
        'total_pages' => $totalPages,
        'current_page' => $page
    ]
]);
?>