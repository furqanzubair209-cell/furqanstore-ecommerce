<?php
session_start();
require_once '../config/db.php';

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Get statistics
$totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'")->fetch_assoc()['count'];
$totalOrders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$pendingOrders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")->fetch_assoc()['count'];
$totalRevenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Furqan Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: #f1f5f9;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 280px;
            background: #0f172a;
            color: white;
            padding: 2rem 1rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
            color: #38bdf8;
        }
        
        .sidebar nav a {
            display: block;
            padding: 1rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .sidebar nav a:hover, .sidebar nav a.active {
            background: #1e293b;
            color: white;
        }
        
        .sidebar nav a i {
            margin-right: 10px;
            width: 20px;
        }
        
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
        }
        
        .stat-card .icon {
            float: right;
            font-size: 2rem;
            color: #38bdf8;
        }
        
        .recent-orders {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .recent-orders h3 {
            margin-bottom: 1rem;
            color: #0f172a;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        th {
            background: #f8fafc;
            font-weight: 600;
            color: #0f172a;
        }
        
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-processing { background: #dbeafe; color: #2563eb; }
        .status-shipped { background: #d1fae5; color: #059669; }
        .status-delivered { background: #d1fae5; color: #059669; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background: #38bdf8;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0284c7;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2><i class="fas fa-store"></i> FurqanStore</h2>
            <nav>
                <a href="index.php" class="active"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="products.php"><i class="fas fa-box"></i> Products</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="users.php"><i class="fas fa-users"></i> Users</a>
                <a href="../index.php"><i class="fas fa-store"></i> View Store</a>
                <a href="../api/auth.php?action=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
                <div><i class="fas fa-user-circle"></i> Welcome, <?php echo $_SESSION['user_name']; ?>!</div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-box icon"></i>
                    <h3>Total Products</h3>
                    <div class="value"><?php echo $totalProducts; ?></div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-users icon"></i>
                    <h3>Total Users</h3>
                    <div class="value"><?php echo $totalUsers; ?></div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-shopping-cart icon"></i>
                    <h3>Total Orders</h3>
                    <div class="value"><?php echo $totalOrders; ?></div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-clock icon"></i>
                    <h3>Pending Orders</h3>
                    <div class="value"><?php echo $pendingOrders; ?></div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-rupee-sign icon"></i>
                    <h3>Total Revenue</h3>
                    <div class="value">PKR <?php echo number_format($totalRevenue ?? 0, 0); ?></div>
                </div>
            </div>
            
            <div class="recent-orders">
                <h3><i class="fas fa-history"></i> Recent Orders</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recentOrders = $conn->query("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10");
                        if ($recentOrders->num_rows > 0):
                        while ($order = $recentOrders->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $order['order_number']; ?></td>
                            <td><?php echo $order['full_name']; ?></td>
                            <td>PKR <?php echo number_format($order['total_amount'], 0); ?></td>
                            <td><span class="status status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td><a href="orders.php" class="btn btn-primary">View</a></td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No orders found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
