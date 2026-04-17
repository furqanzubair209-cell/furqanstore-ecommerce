<?php
// ============================================
// ADMIN: approve_vendors.php
// ============================================
require_once '../config/db.php';

if (!isLoggedIn() || (!isRole('super_admin') && !isRole('admin'))) {
    redirect('auth/login.php');
}

$conn = getConnection();
$pendingVendors = $conn->query("SELECT * FROM users WHERE role = 'vendor' AND status = 'pending' ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Vendors - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); color: #fff; }
        .admin-container { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 280px; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); padding: 2rem 1.5rem; border-right: 1px solid rgba(255,255,255,0.1); }
        .admin-sidebar h2 { margin-bottom: 2rem; }
        .admin-sidebar nav a { display: block; padding: 0.8rem 1rem; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 12px; margin-bottom: 0.5rem; }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { background: rgba(99,102,241,0.2); color: white; }
        .admin-main { flex: 1; padding: 2rem; }
        .vendors-table { background: rgba(255,255,255,0.03); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); }
        .vendors-table table { width: 100%; border-collapse: collapse; }
        .vendors-table th, .vendors-table td { padding: 1rem; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .btn-sm { padding: 4px 12px; border-radius: 8px; border: none; cursor: pointer; font-size: 0.75rem; }
        .btn-success { background: rgba(16,185,129,0.2); color: #10b981; }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-sidebar">
        <h2><i class="fas fa-crown"></i> Admin Panel</h2>
        <nav>
            <a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_products.php"><i class="fas fa-box"></i> Manage Products</a>
            <a href="approve_vendors.php" class="active"><i class="fas fa-store"></i> Approve Vendors</a>
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Store</a>
        </nav>
    </div>
    <div class="admin-main">
        <div class="admin-header" style="display: flex; justify-content: space-between; margin-bottom: 2rem;">
            <h1>Pending Vendor Approvals</h1>
            <a href="../auth/logout.php" style="background: rgba(239,68,68,0.2); padding: 0.5rem 1rem; border-radius: 12px; color: #ef4444; text-decoration: none;">Logout</a>
        </div>
        
        <div class="vendors-table">
            <table>
                <thead><tr><th>ID</th><th>Vendor Name</th><th>Email</th><th>Phone</th><th>Registered</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($pendingVendors as $vendor): ?>
                    <tr>
                        <td><?php echo $vendor['id']; ?></td>
                        <td><?php echo htmlspecialchars($vendor['vendor_name'] ?? $vendor['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($vendor['email']); ?></td>
                        <td><?php echo htmlspecialchars($vendor['phone']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($vendor['created_at'])); ?></td>
                        <td>
                            <form action="../actions/user_actions.php" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $vendor['id']; ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn-sm btn-success">Approve Vendor</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($pendingVendors)): ?>
                    <tr><td colspan="6" style="text-align: center;">No pending vendors</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
