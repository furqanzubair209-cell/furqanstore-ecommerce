<?php
// ============================================
// VENDOR: add_product.php
// ============================================
require_once '../config/db.php';

if (!isLoggedIn() || !isRole('vendor')) {
    redirect('auth/login.php');
}

$conn = getConnection();
$categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $image_url = $_POST['image_url'] ?? '';
    $vendor_id = $_SESSION['user_id'];
    
    if ($name && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category_id, image_url, vendor_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'active')");
        $stmt->bind_param("ssdiisi", $name, $description, $price, $stock, $category_id, $image_url, $vendor_id);
        if ($stmt->execute()) {
            $success = "Product added successfully!";
        } else {
            $error = "Failed to add product";
        }
    } else {
        $error = "Please fill all required fields";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Vendor</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); color: #fff; }
        .vendor-container { display: flex; min-height: 100vh; }
        .vendor-sidebar { width: 280px; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); padding: 2rem 1.5rem; border-right: 1px solid rgba(255,255,255,0.1); }
        .vendor-sidebar nav a { display: block; padding: 0.8rem 1rem; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 12px; margin-bottom: 0.5rem; }
        .vendor-sidebar nav a:hover { background: rgba(99,102,241,0.2); color: white; }
        .vendor-main { flex: 1; padding: 2rem; }
        .form-container { background: rgba(255,255,255,0.03); border-radius: 20px; padding: 2rem; max-width: 600px; border: 1px solid rgba(255,255,255,0.1); }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: rgba(255,255,255,0.7); }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: white; font-family: inherit; }
        .btn-submit { background: linear-gradient(135deg, #06b6d4, #3b82f6); border: none; padding: 0.8rem 2rem; border-radius: 12px; color: white; cursor: pointer; font-weight: 600; }
        .alert { padding: 1rem; border-radius: 12px; margin-bottom: 1rem; }
        .alert-success { background: rgba(16,185,129,0.2); color: #10b981; }
        .alert-error { background: rgba(239,68,68,0.2); color: #ef4444; }
    </style>
</head>
<body>
<div class="vendor-container">
    <div class="vendor-sidebar">
        <h2><i class="fas fa-store"></i> Vendor Panel</h2>
        <nav>
            <a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="add_product.php" style="background: rgba(99,102,241,0.2);"><i class="fas fa-plus-circle"></i> Add Product</a>
            <a href="manage_products.php"><i class="fas fa-box"></i> Manage Products</a>
            <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Store</a>
        </nav>
    </div>
    <div class="vendor-main">
        <h1 style="margin-bottom: 2rem;">Add New Product</h1>
        
        <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>Price (PKR) *</label>
                    <input type="number" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Stock *</label>
                    <input type="number" name="stock" value="0" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id">
                        <option value="0">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" placeholder="https://images.unsplash.com/...">
                </div>
                <button type="submit" class="btn-submit">Add Product</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
