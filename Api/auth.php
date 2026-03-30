<?php
// api/auth.php
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'register':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Check if user exists
            $checkSql = "SELECT id FROM users WHERE email = ?";
            $stmt = $conn->prepare($checkSql);
            $stmt->bind_param("s", $data['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                jsonResponse(false, 'Email already registered');
            }
            
            // Hash password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Insert new user
            $sql = "INSERT INTO users (email, password, full_name, phone) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", 
                $data['email'], 
                $hashedPassword, 
                $data['full_name'], 
                $data['phone']
            );
            
            if ($stmt->execute()) {
                $userId = $conn->insert_id;
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_email'] = $data['email'];
                $_SESSION['user_name'] = $data['full_name'];
                
                jsonResponse(true, 'Registration successful', [
                    'user_id' => $userId,
                    'email' => $data['email'],
                    'full_name' => $data['full_name']
                ]);
            } else {
                jsonResponse(false, 'Registration failed');
            }
        }
        break;
        
    case 'login':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "SELECT id, email, password, full_name FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $data['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                jsonResponse(false, 'User not found');
            }
            
            $user = $result->fetch_assoc();
            
            if (password_verify($data['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['full_name'];
                
                jsonResponse(true, 'Login successful', [
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'full_name' => $user['full_name']
                ]);
            } else {
                jsonResponse(false, 'Invalid password');
            }
        }
        break;
        
    case 'logout':
        session_destroy();
        jsonResponse(true, 'Logged out successfully');
        break;
        
    case 'check':
        if (isset($_SESSION['user_id'])) {
            jsonResponse(true, 'Logged in', [
                'user_id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'],
                'full_name' => $_SESSION['user_name']
            ]);
        } else {
            jsonResponse(false, 'Not logged in');
        }
        break;
}
?>
