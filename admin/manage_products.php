<?php
// ============================================
// ADMIN: manage_products.php
// ============================================
require_once '../config/db.php';

if (!isLoggedIn() || (!isRole('super_admin') && !isRole('admin'))) {
    redirect('auth/login.php');
}

$conn = getConnection();
$products = $conn->query("
    SELECT p.*, u.full_name as vendor_name, c.name as category_name 
    FROM products p 
    LEFT JOIN users u ON p.vendor_id = u.id 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.created_at DESC
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Admin</title>
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
        .products-table { background: rgba(255,255,255,0.03); border-radius: 20px; overflow-x: auto; border: 1px solid rgba(255,255,255,0.05); }
        .products-table table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .products-table th, .products-table td { padding: 1rem; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
        .btn-sm { padding: 4px 12px; border-radius: 8px; border: none; cursor: pointer; font-size: 0.75rem; }
        .btn-danger { background: rgba(239,68,68,0.2); color: #ef4444; }
        .badge { padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; }
        .badge-active { background: rgba(16,185,129,0.2); color: #10b981; }
        .badge-pending { background: rgba(245,158,11,0.2); color: #f59e0b; }
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
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_products.php" class="active"><i class="fas fa-box"></i> Manage Products</a>
            <a href="approve_vendors.php"><i class="fas fa-store"></i> Approve Vendors</a>
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Store</a>
        </nav>
    </div>
    <div class="admin-main">
        <div class="admin-header" style="display: flex; justify-content: space-between; margin-bottom: 2rem;">
            <h1>Manage Products</h1>
            <a href="../auth/logout.php" style="background: rgba(239,68,68,0.2); padding: 0.5rem 1rem; border-radius: 12px; color: #ef4444; text-decoration: none;">Logout</a>
        </div>
        
        <div class="products-table">
            <table>
                <thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Vendor</th><th>Category</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/50'); ?>" class="product-img" onerror="this.src='https://via.placeholder.com/50'"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>PKR <?php echo number_format($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['vendor_name'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                        <td><?php echo $product['stock']; ?></td>
                        <td><span class="badge badge-<?php echo $product['status']; ?>"><?php echo ucfirst($product['status']); ?></span></td>
                        <td>
                            <form action="../actions/product_actions.php" method="POST" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
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
