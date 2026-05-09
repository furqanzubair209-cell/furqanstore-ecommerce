<?php
require_once('../../config/db.php');
header('Content-Type: application/json');

$conn = getConnection();
$categories = getCategories($conn);

echo json_encode([
    'success' => true,
    'data' => $categories
]);
?>
