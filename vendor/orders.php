<?php
// ============================================
// VENDOR: orders.php
// ============================================
require_once '../config/db.php';

if (!isLoggedIn() || !isRole('vendor')) {
    redirect('auth/login.php');
}

$conn = getConnection();
$vendor_id = $_SESSION['user_id'];

$orders = $conn->query("
    SELECT oi.*, o.created_at, o.status, o.shipping_address, p.name as product_name, u.full_name as customer_name
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    JOIN products p ON oi.product_id = p.id
    JOIN users u ON o.user_id = u.id
    WHERE oi.vendor_id = $vendor_id
    ORDER BY o.created_at DESC
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - FurqanStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
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
                <a href="dashboard.php" class="nav-link">
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
                <a href="orders.php" class="nav-link active">
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
                <h1 class="reveal stagger-1">My Orders</h1>
                <p class="reveal stagger-2">Track your sales and customer orders</p>
            </div>
            <div class="header-actions">
                <a href="../auth/logout.php" class="btn-premium" style="background: var(--danger);">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </header>

        <div class="reveal stagger-3">
            <div class="table-premium-container">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Shipping Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                <i class="fas fa-shopping-cart" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.3;"></i>
                                No orders received yet.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><span style="font-weight: 600; color: var(--primary);">#<?php echo $order['order_id']; ?></span></td>
                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td>
                                    <div style="font-weight: 600;"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                                </td>
                                <td><span class="badge-premium" style="background: var(--nav-hover-bg); color: var(--text-primary);"><?php echo $order['quantity']; ?></span></td>
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
                                <td style="max-width: 200px;">
                                    <small style="color: var(--text-secondary); display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($order['shipping_address'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($order['shipping_address'] ?? 'No address provided'); ?>
                                    </small>
                                </td>
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
    });
</script>
</body>
</html>
