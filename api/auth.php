<?php
// api/auth.php
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Make sure jsonResponse function exists
if (!function_exists('jsonResponse')) {
    function jsonResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}

switch($action) {
    case 'register':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate input
            if (empty($data['full_name']) || empty($data['email']) || empty($data['phone']) || empty($data['password'])) {
                jsonResponse(false, 'All fields are required');
            }
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                jsonResponse(false, 'Invalid email format');
            }
            
            if (strlen($data['password']) < 6) {
                jsonResponse(false, 'Password must be at least 6 characters');
            }
            
            if (!preg_match('/^[0-9]{10,15}$/', $data['phone'])) {
                jsonResponse(false, 'Invalid phone number format');
            }
            
            // Check if email exists
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
            
            // Insert user
            $sql = "INSERT INTO users (full_name, email, phone, password, role) VALUES (?, ?, ?, ?, 'user')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $data['full_name'], $data['email'], $data['phone'], $hashedPassword);
            
            if ($stmt->execute()) {
                $userId = $conn->insert_id;
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_email'] = $data['email'];
                $_SESSION['user_name'] = $data['full_name'];
                $_SESSION['user_role'] = 'user';
                
                jsonResponse(true, 'Registration successful', [
                    'user_id' => $userId,
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'role' => 'user'
                ]);
            } else {
                jsonResponse(false, 'Registration failed');
            }
        }
        break;
        
    case 'login':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['email']) || empty($data['password'])) {
                jsonResponse(false, 'Email and password are required');
            }
            
            $sql = "SELECT id, email, password, full_name, phone, address, role FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $data['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                jsonResponse(false, 'Invalid email or password');
            }
            
            $user = $result->fetch_assoc();
            
            if (password_verify($data['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];
                
                jsonResponse(true, 'Login successful', [
                    'user_id' => $user['id'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'address' => $user['address'],
                    'role' => $user['role']
                ]);
            } else {
                jsonResponse(false, 'Invalid email or password');
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
                'full_name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'role' => $_SESSION['user_role']
            ]);
        } else {
            jsonResponse(false, 'Not logged in');
        }
        break;
        
    case 'update_profile':
        if (!isset($_SESSION['user_id'])) {
            jsonResponse(false, 'Please login first');
        }
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $userId = $_SESSION['user_id'];
            
            $sql = "UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $data['full_name'], $data['phone'], $data['address'], $userId);
            
            if ($stmt->execute()) {
                $_SESSION['user_name'] = $data['full_name'];
                jsonResponse(true, 'Profile updated successfully');
            } else {
                jsonResponse(false, 'Failed to update profile');
            }
        }
        break;
        
    case 'change_password':
        if (!isset($_SESSION['user_id'])) {
            jsonResponse(false, 'Please login first');
        }
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $userId = $_SESSION['user_id'];
            
            // Get current password
            $sql = "SELECT password FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            if (!password_verify($data['current_password'], $user['password'])) {
                jsonResponse(false, 'Current password is incorrect');
            }
            
            if (strlen($data['new_password']) < 6) {
                jsonResponse(false, 'New password must be at least 6 characters');
            }
            
            $newHashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
            $updateSql = "UPDATE users SET password = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("si", $newHashedPassword, $userId);
            
            if ($updateStmt->execute()) {
                jsonResponse(true, 'Password changed successfully');
            } else {
                jsonResponse(false, 'Failed to change password');
            }
        }
        break;
        
    default:
        jsonResponse(false, 'Invalid action');
        break;
}
?>
