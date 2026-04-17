<?php
session_start();
require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id, full_name, email, password, role, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user) {
            // Verify password (works with bcrypt or MD5)
            $password_valid = false;
            if (password_verify($password, $user['password'])) {
                $password_valid = true;
            } elseif (md5($password) === $user['password']) {
                $password_valid = true;
            }
            
            if ($password_valid && $user['status'] === 'active') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on role
                if ($user['role'] === 'super_admin' || $user['role'] === 'admin') {
                    header('Location: ../admin/dashboard.php');
                } elseif ($user['role'] === 'vendor') {
                    header('Location: ../vendor/dashboard.php');
                } else {
                    header('Location: ../index.php');
                }
                exit();
            } else {
                $error = 'Invalid credentials or inactive account';
            }
        } else {
            $error = 'User not found';
        }
    } else {
        $error = 'Email and password required';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - FurqanStore</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 40px; border-radius: 20px; width: 350px; text-align: center; }
        .login-box h2 { color: white; margin-bottom: 30px; }
        .login-box input { width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 10px; background: rgba(255,255,255,0.2); color: white; }
        .login-box button { width: 100%; padding: 12px; background: linear-gradient(135deg, #6366f1, #ec4899); border: none; border-radius: 10px; color: white; font-weight: bold; cursor: pointer; }
        .error { color: #ff6b6b; margin-bottom: 15px; }
        .register-link { color: white; margin-top: 15px; display: block; }
        .register-link a { color: #818cf8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin / Vendor Login</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            <a href="../index.php">← Back to Store</a>
        </div>
    </div>
</body>
</html>