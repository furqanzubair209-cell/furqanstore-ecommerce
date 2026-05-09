<?php
require_once('../../config/db.php');
header('Content-Type: application/json');

$conn = getConnection();
$result = $conn->query("SELECT id, full_name as name FROM users WHERE role = 'vendor' AND status = 'active' ORDER BY full_name");
$vendors = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'success' => true,
    'data' => $vendors
]);
?>
