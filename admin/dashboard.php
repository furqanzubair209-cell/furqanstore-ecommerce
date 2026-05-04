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
                <div class="logo-icon"><i class="fas fa-crown"></i></div>
                <div class="logo-text">
                    <span class="logo-furqan">Admin</span>
                    <span class="logo-store">Panel</span>
                </div>
            </div>

            <div class="user-card">
                <div class="user-avatar">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff&size=80" alt="Admin">
                    <div class="online-dot"></div>
                </div>
                <div class="user-info-panel">
                    <h4><?php echo htmlspecialchars($_SESSION['full_name']); ?></h4>
                    <p>System Administrator</p>
                </div>
            </div>

            <nav class="premium-nav">
                <a href="dashboard.php" class="nav-link active">
                    <div class="nav-icon"><i class="fas fa-chart-line"></i></div>
                    <span>Dashboard</span>
                    <div class="nav-indicator"></div>
                </a>
                <a href="manage_users.php" class="nav-link">
                    <div class="nav-icon"><i class="fas fa-users"></i></div>
                    <span>Manage Users</span>
                    <div class="nav-indicator"></div>
                </a>
                <a href="manage_products.php" class="nav-link">
                    <div class="nav-icon"><i class="fas fa-box"></i></div>
                    <span>Manage Products</span>
                    <div class="nav-indicator"></div>
                </a>
                <a href="approve_vendors.php" class="nav-link">
                    <div class="nav-icon"><i class="fas fa-store"></i></div>
                    <span>Approve Vendors</span>
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
                <h1 class="reveal stagger-1">Admin Overview</h1>
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
                <div class="stat-icon" style="background: rgba(99, 102, 241, 0.1);"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_users']; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="card-premium stat-card-premium">
                <div class="stat-icon" style="background: rgba(6, 182, 212, 0.1);"><i class="fas fa-box"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_products']; ?></h3>
                    <p>Total Products</p>
                </div>
            </div>
            <div class="card-premium stat-card-premium">
                <div class="stat-icon" style="background: rgba(236, 72, 153, 0.1);"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_orders']; ?></h3>
                    <p>Total Orders</p>
                </div>
            </div>
            <div class="card-premium stat-card-premium">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1);"><i class="fas fa-wallet"></i></div>
                <div class="stat-info">
                    <h3>PKR <?php echo number_format($stats['total_revenue']); ?></h3>
                    <p>Revenue</p>
                </div>
            </div>
        </div>

        <!-- Global Analytics Chart -->
        <div class="reveal stagger-3">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title"><i class="fas fa-globe"></i> Global Sales Analytics</h3>
                    <div class="chart-actions">
                        <select class="select-premium" style="padding: 5px 10px; font-size: 0.8rem;">
                            <option>All Vendors</option>
                        </select>
                    </div>
                </div>
                <canvas id="adminGlobalChart"></canvas>
            </div>
        </div>

        <div class="reveal stagger-4">
            <h2 class="section-title">Recent <span>System Activity</span></h2>
            <div class="table-premium-container">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">No orders found.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><span style="font-weight: 600; color: var(--primary);">#<?php echo $order['id']; ?></span></td>
                                <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                <td><span style="font-weight: 700;">PKR <?php echo number_format($order['total']); ?></span></td>
                                <td>
                                    <span class="badge-premium" style="background: var(--success-bg); color: var(--success);">
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
    document.addEventListener('DOMContentLoaded', () => {
        const reveals = document.querySelectorAll('.reveal');
        reveals.forEach(el => el.classList.add('active'));

        // Initialize Global Chart
        const ctx = document.getElementById('adminGlobalChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Total Revenue (PKR)',
                        data: [450000, 620000, 580000, 850000, 920000, 1100000],
                        backgroundColor: 'rgba(6, 182, 212, 0.2)',
                        borderColor: '#06b6d4',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    });
</script>
</body>
</html>
