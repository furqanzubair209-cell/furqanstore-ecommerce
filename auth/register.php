<?php
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'customer';
    
    if (empty($full_name) || empty($email) || empty($phone) || empty($password)) {
        $error = 'All fields required';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $conn = getConnection();
        
        // Check if email exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Email already registered';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $status = ($role === 'vendor') ? 'pending' : 'active';
            $vendor_name = ($role === 'vendor') ? $full_name : null;
            
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password, role, status, vendor_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $full_name, $email, $phone, $hashed, $role, $status, $vendor_name);
            
            if ($stmt->execute()) {
                $success = ($role === 'vendor') 
                    ? 'Vendor account created! Wait for admin approval.' 
                    : 'Account created! You can now login.';
            } else {
                $error = 'Registration failed';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - FurqanStore</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .register-box { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 40px; border-radius: 20px; width: 400px; text-align: center; }
        .register-box h2 { color: white; margin-bottom: 30px; }
        .register-box input, .register-box select { width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 10px; background: rgba(255,255,255,0.2); color: white; }
        .register-box button { width: 100%; padding: 12px; background: linear-gradient(135deg, #6366f1, #ec4899); border: none; border-radius: 10px; color: white; font-weight: bold; cursor: pointer; }
        .error { color: #ff6b6b; margin-bottom: 15px; }
        .success { color: #51cf66; margin-bottom: 15px; }
        .login-link { color: white; margin-top: 15px; display: block; }
        .login-link a { color: #818cf8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Create Account</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password (min 6 chars)" required>
            <select name="role">
                <option value="customer">Customer</option>
                <option value="vendor">Vendor (Sell Products)</option>
            </select>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a><br>
            <a href="../index.php">← Back to Store</a>
        </div>
    </div>
</body>
</html>