<?php
// ============================================
// ADMIN DASHBOARD: admin/dashboard.php
// ============================================
require_once '../config/db.php';

// Check if user is super_admin or admin
if (!isLoggedIn() || (!isRole('super_admin') && !isRole('admin'))) {
    redirect('auth/login.php');
}

$conn = getConnection();

// Get statistics
$stats = [];

// Total users
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$stats['total_users'] = $result->fetch_assoc()['count'];

// Total products
$result = $conn->query("SELECT COUNT(*) as count FROM products");
$stats['total_products'] = $result->fetch_assoc()['count'];

// Total orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders");
$stats['total_orders'] = $result->fetch_assoc()['count'];

// Total revenue
$result = $conn->query("SELECT SUM(total) as total FROM orders WHERE status != 'cancelled'");
$stats['total_revenue'] = $result->fetch_assoc()['total'] ?? 0;

// Pending vendors
$result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'vendor' AND status = 'pending'");
$stats['pending_vendors'] = $result->fetch_assoc()['count'];

// Recent orders
$recentOrders = $conn->query("
    SELECT o.*, u.full_name 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FurqanStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); color: #fff; }
        .admin-container { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 280px; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); padding: 2rem 1.5rem; border-right: 1px solid rgba(255,255,255,0.1); }
        .admin-sidebar h2 { margin-bottom: 2rem; font-size: 1.5rem; }
        .admin-sidebar h2 i { color: #6366f1; margin-right: 10px; }
        .admin-sidebar nav a { display: block; padding: 0.8rem 1rem; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 12px; margin-bottom: 0.5rem; transition: all 0.3s; }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { background: rgba(99,102,241,0.2); color: white; }
        .admin-sidebar nav a i { width: 24px; margin-right: 10px; }
        .admin-main { flex: 1; padding: 2rem; overflow-y: auto; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: rgba(255,255,255,0.05); border-radius: 20px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.1); }
        .stat-card i { font-size: 2rem; color: #6366f1; margin-bottom: 1rem; }
        .stat-card h3 { font-size: 1.8rem; margin-bottom: 0.25rem; }
        .stat-card p { color: rgba(255,255,255,0.6); }
        .recent-table { background: rgba(255,255,255,0.03); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); }
        .recent-table table { width: 100%; border-collapse: collapse; }
        .recent-table th, .recent-table td { padding: 1rem; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .recent-table th { color: #6366f1; font-weight: 600; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; background: rgba(16,185,129,0.2); color: #10b981; }
        .logout-btn { background: rgba(239,68,68,0.2); color: #ef4444; border: none; padding: 0.5rem 1rem; border-radius: 12px; cursor: pointer; }
        .logout-btn:hover { background: rgba(239,68,68,0.3); }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-sidebar">
        <h2><i class="fas fa-crown"></i> Admin Panel</h2>
        <nav>
            <a href="dashboard.php" class="active"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_products.php"><i class="fas fa-box"></i> Manage Products</a>
            <a href="approve_vendors.php"><i class="fas fa-store"></i> Approve Vendors</a>
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Store</a>
        </nav>
    </div>
    <div class="admin-main">
        <div class="admin-header">
            <h1>Dashboard</h1>
            <div>
                <span style="margin-right: 1rem;">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                <a href="../auth/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card"><i class="fas fa-users"></i><h3><?php echo $stats['total_users']; ?></h3><p>Total Users</p></div>
            <div class="stat-card"><i class="fas fa-box"></i><h3><?php echo $stats['total_products']; ?></h3><p>Total Products</p></div>
            <div class="stat-card"><i class="fas fa-shopping-cart"></i><h3><?php echo $stats['total_orders']; ?></h3><p>Total Orders</p></div>
            <div class="stat-card"><i class="fas fa-dollar-sign"></i><h3>PKR <?php echo number_format($stats['total_revenue']); ?></h3><p>Total Revenue</p></div>
            <div class="stat-card"><i class="fas fa-clock"></i><h3><?php echo $stats['pending_vendors']; ?></h3><p>Pending Vendors</p></div>
        </div>
        
        <div class="recent-table">
            <h3 style="padding: 1rem;">Recent Orders</h3>
            <table>
                <thead><tr><th>Order ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                        <td>PKR <?php echo number_format($order['total']); ?></td>
                        <td><span class="status-badge"><?php echo ucfirst($order['status']); ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </div>
        </div>
    </div>
</div>
</body>
</html>