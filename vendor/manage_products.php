<?php
// ============================================
// VENDOR: manage_products.php
// ============================================
require_once '../config/db.php';

if (!isLoggedIn() || !isRole('vendor')) {
    redirect('auth/login.php');
}

$conn = getConnection();
$vendor_id = $_SESSION['user_id'];
$products = $conn->query("SELECT * FROM products WHERE vendor_id = $vendor_id ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - FurqanStore</title>
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
                <a href="manage_products.php" class="nav-link active">
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
                <h1 class="reveal stagger-1">My Products</h1>
                <p class="reveal stagger-2">Manage your inventory and listings</p>
            </div>
            <div class="header-actions" style="display: flex; gap: 1rem;">
                <a href="add_product.php" class="btn-premium">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
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
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                <i class="fas fa-box-open" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.3;"></i>
                                You haven't added any products yet.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/60'); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 12px; border: 1px solid var(--border-light);"
                                         onerror="this.src='https://via.placeholder.com/60'">
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <small style="color: var(--text-muted);">ID: #<?php echo $product['id']; ?></small>
                                </td>
                                <td><span style="font-weight: 700;">PKR <?php echo number_format($product['price']); ?></span></td>
                                <td>
                                    <span class="badge-premium <?php echo $product['stock'] < 10 ? 'pulse-danger' : ''; ?>" 
                                          style="background: <?php echo $product['stock'] < 10 ? 'rgba(239, 68, 68, 0.15)' : 'var(--success-bg)'; ?>; 
                                                 color: <?php echo $product['stock'] < 10 ? 'var(--danger)' : 'var(--success)'; ?>; 
                                                 font-weight: 600;">
                                        <i class="fas <?php echo $product['stock'] < 10 ? 'fa-exclamation-triangle' : 'fa-check-circle'; ?>"></i>
                                        <?php echo $product['stock']; ?> in stock
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-premium" style="background: var(--success-bg); color: var(--success);">
                                        <?php echo ucfirst($product['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 10px;">
                                        <button class="btn-premium" style="padding: 0.5rem 1rem; font-size: 0.8rem; background: var(--nav-hover-bg); color: var(--text-primary);">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form action="../actions/product_actions.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn-premium" style="padding: 0.5rem 1rem; font-size: 0.8rem; background: rgba(239, 68, 68, 0.15); color: var(--danger);" onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
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
