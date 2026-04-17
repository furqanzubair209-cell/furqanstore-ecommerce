<?php
// ============================================
// CONFIGURATION FILE: config/db.php
// ============================================
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'furqanstore_db');

// Site configuration
define('SITE_URL', 'http://localhost/furqanstore/');
define('SITE_NAME', 'FurqanStore');

// Commission for vendors (percentage)
define('VENDOR_COMMISSION', 10);

// Create connection
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

function redirect($url) {
    header("Location: " . SITE_URL . $url);
    exit();
}

function showMessage($msg, $type = 'success') {
    $_SESSION['message'] = $msg;
    $_SESSION['message_type'] = $type;
}

function getMessage() {
    if (isset($_SESSION['message'])) {
        $msg = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'success';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        return ['message' => $msg, 'type' => $type];
    }
    return null;
}

function escape($str) {
    $conn = getConnection();
    return $conn->real_escape_string($str);
}

function getCategories($conn) {
    $result = $conn->query("SELECT * FROM categories ORDER BY name");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getProducts($conn, $filters = []) {
    $sql = "SELECT p.*, u.name as vendor_name, c.name as category_name 
            FROM products p 
            LEFT JOIN users u ON p.vendor_id = u.id 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.status = 'active'";
    
    if (isset($filters['category']) && $filters['category'] !== 'all') {
        $sql .= " AND p.category_id = " . intval($filters['category']);
    }
    if (isset($filters['vendor']) && $filters['vendor'] !== 'all') {
        $sql .= " AND p.vendor_id = " . intval($filters['vendor']);
    }
    if (isset($filters['search']) && !empty($filters['search'])) {
        $search = $conn->real_escape_string($filters['search']);
        $sql .= " AND p.name LIKE '%$search%'";
    }
    
    $sort = $filters['sort'] ?? 'default';
    if ($sort === 'price_asc') $sql .= " ORDER BY p.price ASC";
    elseif ($sort === 'price_desc') $sql .= " ORDER BY p.price DESC";
    elseif ($sort === 'rating') $sql .= " ORDER BY p.rating DESC";
    else $sql .= " ORDER BY p.id DESC";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>
