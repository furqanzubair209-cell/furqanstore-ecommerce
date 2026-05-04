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
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="dark-mode">
<div class="app">
    <!-- Premium Cursor -->
    <div class="cursor"></div>
    <div class="cursor-follower"></div>

    <!-- Premium Sidebar -->
    <aside class="premium-sidebar">
        <div class="sidebar-glow"></div>
        <div class="sidebar-content">
            <div class="logo-area">
                <div class="logo-icon"><i class="fas fa-store"></i></div>
                <div class="logo-text">
                    <span class="logo-furqan">Vendor</span>
                    <span class="logo-store">Panel</span>
                </div>
            </div>

            <div class="user-card">
                <div class="user-avatar">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['full_name']); ?>&background=6366f1&color=fff&size=80" alt="Vendor">
                    <div class="online-dot"></div>
                </div>
                <div class="user-info-panel">
                    <h4><?php echo htmlspecialchars($_SESSION['full_name']); ?></h4>
                    <p>Verified Vendor</p>
                </div>
            </div>

            <nav class="premium-nav">
                <a href="dashboard.php" class="nav-link active">
                    <div class="nav-icon"><i class="fas fa-chart-line"></i></div>
                    <span>Dashboard</span>
                    <div class="nav-indicator"></div>
                </a>
                <a href="add_product.php" class="nav-link">
                    <div class="nav-icon"><i class="fas fa-plus-circle"></i></div>
                    <span>Add Product</span>
                    <div class="nav-indicator"></div>
                </a>
                <a href="manage_products.php" class="nav-link">
                    <div class="nav-icon"><i class="fas fa-box"></i></div>
                    <span>Manage Products</span>
                    <div class="nav-indicator"></div>
                </a>
                <a href="orders.php" class="nav-link">
                    <div class="nav-icon"><i class="fas fa-shopping-cart"></i></div>
                    <span>Orders</span>
                    <div class="nav-indicator"></div>
                </a>
                <a href="../index.php" class="nav-link">
                    <div class="nav-icon"><i class="fas fa-arrow-left"></i></div>
                    <span>Back to Store</span>
                    <div class="nav-indicator"></div>
                </a>
            </nav>
        </div>
    </aside>

    <main class="dashboard-main">
        <header class="dashboard-header">
            <div>
                <h1 class="reveal stagger-1">Dashboard Overview</h1>
                <p class="reveal stagger-2">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
            </div>
            <div class="header-actions">
                <a href="../auth/logout.php" class="btn-premium" style="background: var(--danger);">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="stats-grid-premium reveal stagger-3">
            <div class="card-premium stat-card-premium">
                <div class="stat-icon"><i class="fas fa-box"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_products']; ?></h3>
                    <p>Total Products</p>
                </div>
            </div>
            <div class="card-premium stat-card-premium">
                <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_orders']; ?></h3>
                    <p>Total Orders</p>
                </div>
            </div>
            <div class="card-premium stat-card-premium">
                <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                <div class="stat-info">
                    <h3>PKR <?php echo number_format($stats['total_earnings']); ?></h3>
                    <p>Total Earnings</p>
                </div>
            </div>
        </div>
        
        <!-- Sales Analytics Chart -->
        <div class="reveal stagger-3">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title"><i class="fas fa-chart-line"></i> Sales Analytics</h3>
                    <div class="chart-actions">
                        <select class="select-premium" style="padding: 5px 10px; font-size: 0.8rem;">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                        </select>
                    </div>
                </div>
                <canvas id="vendorSalesChart"></canvas>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="reveal stagger-4">
            <div class="section-header-premium" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <h2 class="section-title" style="margin: 0;">Recent <span>Orders</span></h2>
                <a href="orders.php" class="view-all">View All Orders <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="table-premium-container">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                <i class="fas fa-shopping-basket" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.3;"></i>
                                No orders received yet.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><span style="font-weight: 600; color: var(--primary);">#<?php echo $order['order_id']; ?></span></td>
                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><span style="font-weight: 700;">PKR <?php echo number_format($order['price'] * $order['quantity']); ?></span></td>
                                <td>
                                    <span class="badge-premium" style="background: <?php 
                                        echo $order['status'] === 'delivered' ? 'var(--success-bg)' : ($order['status'] === 'pending' ? 'var(--warning-bg, rgba(245,158,11,0.15))' : 'var(--nav-hover-bg)'); 
                                    ?>; color: <?php 
                                        echo $order['status'] === 'delivered' ? 'var(--success)' : ($order['status'] === 'pending' ? 'var(--warning)' : 'var(--primary-light)'); 
                                    ?>;">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="../script.js"></script>
<script>
    // Add reveal animations
    document.addEventListener('DOMContentLoaded', () => {
        const reveals = document.querySelectorAll('.reveal');
        reveals.forEach(el => el.classList.add('active'));

        // Initialize Sales Chart
        const ctx = document.getElementById('vendorSalesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Earnings (PKR)',
                        data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#94a3b8' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8' }
                        }
                    }
                }
            });
        }
    });
</script>
</body>
</html>
