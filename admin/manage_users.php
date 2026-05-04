<?php
// ============================================
// ADMIN: manage_users.php
// ============================================
require_once '../config/db.php';

if (!isLoggedIn() || (!isRole('super_admin') && !isRole('admin'))) {
    redirect('auth/login.php');
}

$conn = getConnection();
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); color: #fff; }
        .admin-container { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 280px; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); padding: 2rem 1.5rem; border-right: 1px solid rgba(255,255,255,0.1); }
        .admin-sidebar h2 { margin-bottom: 2rem; }
        .admin-sidebar nav a { display: block; padding: 0.8rem 1rem; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 12px; margin-bottom: 0.5rem; }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { background: rgba(99,102,241,0.2); color: white; }
        .admin-main { flex: 1; padding: 2rem; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .users-table { background: rgba(255,255,255,0.03); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); }
        .users-table table { width: 100%; border-collapse: collapse; }
        .users-table th, .users-table td { padding: 1rem; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .badge { padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; }
        .badge-super_admin { background: rgba(99,102,241,0.2); color: #818cf8; }
        .badge-admin { background: rgba(236,72,153,0.2); color: #ec4899; }
        .badge-vendor { background: rgba(6,182,212,0.2); color: #06b6d4; }
        .badge-customer { background: rgba(16,185,129,0.2); color: #10b981; }
        .btn-sm { padding: 4px 12px; border-radius: 8px; border: none; cursor: pointer; font-size: 0.75rem; }
        .btn-danger { background: rgba(239,68,68,0.2); color: #ef4444; }
        .btn-success { background: rgba(16,185,129,0.2); color: #10b981; }
        .back-link { display: inline-block; margin-bottom: 1rem; color: #6366f1; text-decoration: none; }
    </style>
</head>
<body class="dark-mode">
<div class="admin-container">
    <!-- Premium Cursor -->
    <div class="cursor"></div>
    <div class="cursor-follower"></div>

    <div class="admin-sidebar">
        <h2><i class="fas fa-crown"></i> Admin Panel</h2>
        <nav>
            <a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="manage_users.php" class="active"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_products.php"><i class="fas fa-box"></i> Manage Products</a>
            <a href="approve_vendors.php"><i class="fas fa-store"></i> Approve Vendors</a>
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Store</a>
        </nav>
    </div>
    <div class="admin-main">
        <div class="admin-header">
            <h1>Manage Users</h1>
            <a href="../auth/logout.php" style="background: rgba(239,68,68,0.2); padding: 0.5rem 1rem; border-radius: 12px; color: #ef4444; text-decoration: none;">Logout</a>
        </div>
        
        <div class="users-table">
            <table>
                <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><span class="badge badge-<?php echo $user['role']; ?>"><?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?></span></td>
                        <td><span class="badge" style="background: <?php echo $user['status'] === 'active' ? 'rgba(16,185,129,0.2)' : 'rgba(239,68,68,0.2)'; ?>; color: <?php echo $user['status'] === 'active' ? '#10b981' : '#ef4444'; ?>;"><?php echo ucfirst($user['status']); ?></span></td>
                        <td>
                            <?php if ($user['status'] === 'pending'): ?>
                            <form action="../actions/user_actions.php" method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn-sm btn-success">Approve</button>
                            </form>
                            <?php endif; ?>
                            <form action="../actions/user_actions.php" method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="action" value="suspend">
                                <button type="submit" class="btn-sm btn-danger">Suspend</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="../script.js"></script>
</body>
</html>
