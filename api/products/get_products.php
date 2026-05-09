<?php
require_once('../../config/db.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = getConnection();

// Get filter parameters from GET request
$filters = [
    'search' => $_GET['search'] ?? '',
    'category' => $_GET['category'] ?? 'all',
    'vendor' => $_GET['vendor'] ?? 'all',
    'sort' => $_GET['sort'] ?? 'default'
];

$page = intval($_GET['page'] ?? 1);
$limit = 12;

// Get all products matching filters (for total count)
// Note: We need a way to get total count without pagination in getProducts or modify it
// For now, let's get all filtered products and paginate in PHP, or improve getProducts

// Let's modify getProducts to handle pagination if needed, or just slice here
$all_filtered_products = getProducts($conn, $filters);

$total = count($all_filtered_products);
$totalPages = ceil($total / $limit);
$offset = ($page - 1) * $limit;

$paginated_products = array_slice($all_filtered_products, $offset, $limit);

echo json_encode([
    'success' => true,
    'data' => [
        'products' => $paginated_products,
        'total' => $total,
        'total_pages' => $totalPages,
        'current_page' => $page
    ]
]);
?>
