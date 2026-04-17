<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Demo product data
$products = [
    ["id" => 1, "name" => "Premium Sneakers", "price" => 7999, "category" => "footwear", "vendor_name" => "Nike Store", "rating" => 4.5, "reviews" => 128, "image_url" => "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400", "badge" => "hot"],
    ["id" => 2, "name" => "Wireless Headphones", "price" => 12499, "category" => "audio", "vendor_name" => "Sony Official", "rating" => 4.8, "reviews" => 256, "image_url" => "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400", "badge" => "best"],
    ["id" => 3, "name" => "Smart Watch Pro", "price" => 15999, "category" => "electronics", "vendor_name" => "Apple Store", "rating" => 4.6, "reviews" => 89, "image_url" => "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400", "badge" => "new"],
    ["id" => 4, "name" => "Bluetooth Speaker", "price" => 6999, "category" => "audio", "vendor_name" => "JBL Official", "rating" => 4.3, "reviews" => 67, "image_url" => "https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=400"],
    ["id" => 5, "name" => "Leather Backpack", "price" => 8999, "category" => "fashion", "vendor_name" => "Fashion Hub", "rating" => 4.7, "reviews" => 45, "image_url" => "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400", "badge" => "sale"],
    ["id" => 6, "name" => "Mechanical Keyboard", "price" => 10999, "category" => "electronics", "vendor_name" => "Gaming Gear", "rating" => 4.9, "reviews" => 312, "image_url" => "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400", "badge" => "hot"]
];

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'all';

$filtered = $products;
if (!empty($search)) {
    $filtered = array_filter($filtered, function($p) use ($search) {
        return stripos($p['name'], $search) !== false;
    });
}
if ($category !== 'all') {
    $filtered = array_filter($filtered, function($p) use ($category) {
        return $p['category'] === $category;
    });
}

$filtered = array_values($filtered);
$total = count($filtered);
$page = intval($_GET['page'] ?? 1);
$limit = 12;
$offset = ($page - 1) * $limit;
$paginated = array_slice($filtered, $offset, $limit);

echo json_encode([
    'success' => true,
    'data' => [
        'products' => $paginated,
        'total' => $total,
        'total_pages' => ceil($total / $limit),
        'current_page' => $page
    ]
]);
?>
