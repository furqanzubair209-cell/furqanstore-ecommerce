<?php
// config/db.php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'furqan_store_premium';

// Create connection WITHOUT json_encode (to avoid function not defined error)
$conn = new mysqli($host, $username, $password, $database);

// Check connection - simple error message first
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define functions AFTER connection is successful
function jsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        jsonResponse(false, 'Please login first');
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        jsonResponse(false, 'Unauthorized access');
    }
}
?>
