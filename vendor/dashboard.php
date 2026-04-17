<?php
// ============================================
// VENDOR DASHBOARD: vendor/dashboard.php
// ============================================
require_once '../config/db.php';

if (!isLoggedIn() || !isRole('vendor')) {
    redirect('auth/login.php');
}

$conn = getConnection();
$vendor_id = $_SESSION['user_id'];

// Get vendor statistics
$stats = [];

// Products count
$result = $conn->query("SELECT COUNT(*) as count FROM products WHERE vendor_id = $vendor_id");
$stats['total_products'] = $result->fetch_assoc()['count'];

// Orders count (from order_items)
$result = $conn->query("SELECT COUNT(DISTINCT order_id) as count FROM order_items WHERE vendor_id = $vendor_id");
$stats['total_orders'] = $result->fetch_assoc()['count'];

// Total earnings
$result = $conn->query("SELECT SUM(vendor_earning) as total FROM order_items WHERE vendor_id = $vendor_id");
$stats['total_earnings'] = $result->fetch_assoc()['total'] ?? 0;

// Recent orders
$recentOrders = $conn->query("
    SELECT oi.*, o.created_at, o.status, p.name as product_name, u.full_name as customer_name
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    JOIN products p ON oi.product_id = p.id
    JOIN users u ON o.user_id = u.id
    WHERE oi.vendor_id = $vendor_id
    ORDER BY o.created_at DESC
    LIMIT 10
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - FurqanStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); color: #fff; }
        .vendor-container { display: flex; min-height: 100vh; }
        .vendor-sidebar { width: 280px; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); padding: 2rem 1.5rem; border-right: 1px solid rgba(255,255,255,0.1); }
        .vendor-sidebar h2 { margin-bottom: 2rem; }
        .vendor-sidebar nav a { display: block; padding: 0.8rem 1rem; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 12px; margin-bottom: 0.5rem; }
        .vendor-sidebar nav a:hover, .vendor-sidebar nav a.active { background: rgba(99,102,241,0.2); color: white; }
        .vendor-main { flex: 1; padding: 2rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: rgba(255,255,255,0.05); border-radius: 20px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.1); }
        .stat-card i { font-size: 2rem; color: #06b6d4; margin-bottom: 1rem; }
        .stat-card h3 { font-size: 1.8rem; }
        .orders-table { background: rgba(255,255,255,0.03); border-radius: 20px; overflow-x: auto; border: 1px solid rgba(255,255,255,0.05); }
        .orders-table table { width: 100%; border-collapse: collapse; min-width: 600px; }
        .orders-table th, .orders-table td { padding: 1rem; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; }
        .status-pending { background: rgba(245,158,11,0.2); color: #f59e0b; }
        .status-processing { background: rgba(99,102,241,0.2); color: #818cf8; }
        .status-delivered { background: rgba(16,185,129,0.2); color: #10b981; }
        .btn { padding: 0.5rem 1rem; border-radius: 12px; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: linear-gradient(135deg, #06b6d4, #3b82f6); color: white; }
        .btn-danger { background: rgba(239,68,68,0.2); color: #ef4444; }
    </style>
</head>
<body>
<div class="vendor-container">
    <div class="vendor-sidebar">
        <h2><i class="fas fa-store"></i> Vendor Panel</h2>
        <nav>
            <a href="dashboard.php" class="active"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="add_product.php"><i class="fas fa-plus-circle"></i> Add Product</a>
            <a href="manage_products.php"><i class="fas fa-box"></i> Manage Products</a>
            <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Store</a>
        </nav>
    </div>
    <div class="vendor-main">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Vendor Dashboard</h1>
            <div>
                <span style="margin-right: 1rem;">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card"><i class="fas fa-box"></i><h3><?php echo $stats['total_products']; ?></h3><p>Total Products</p></div>
            <div class="stat-card"><i class="fas fa-shopping-cart"></i><h3><?php echo $stats['total_orders']; ?></h3><p>Orders Received</p></div>
            <div class="stat-card"><i class="fas fa-dollar-sign"></i><h3>PKR <?php echo number_format($stats['total_earnings']); ?></h3><p>Total Earnings</p></div>
        </div>
        
        <div class="orders-table">
            <h3 style="padding: 1rem;">Recent Orders</h3>
            <table>
                <thead><tr><th>Order ID</th><th>Product</th><th>Customer</th><th>Quantity</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo $order['quantity']; ?></td>
                        <td>PKR <?php echo number_format($order['price'] * $order['quantity']); ?></td>
                        <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>