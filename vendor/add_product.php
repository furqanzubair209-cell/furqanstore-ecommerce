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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - FurqanStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="dark-mode">
<div class="app">
    <!-- Premium Cursor -->
    <div class="cursor"></div>
    <div class="cursor-follower"></div>

    <!-- Premium Sidebar (Same as main page but for vendor) -->
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
                <a href="add_product.php" class="nav-link active">
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
                <h1 class="reveal stagger-1">Add New Product</h1>
                <p class="reveal stagger-2">Create a new listing for your store</p>
            </div>
            <a href="../auth/logout.php" class="btn-premium" style="background: var(--danger);">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </header>

        <div class="reveal stagger-3">
            <?php if (isset($success)): ?>
            <div class="alert-premium success" style="background: var(--success-bg); color: var(--success); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid var(--success);">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
            <div class="alert-premium error" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid var(--danger);">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <div class="card-premium" style="max-width: 800px;">
                <form method="POST">
                    <div class="form-group-premium">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" class="input-premium" placeholder="e.g. Premium Wireless Headphones" required>
                    </div>

                    <div class="form-group-premium">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="textarea-premium" rows="5" placeholder="Describe your product details..."></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group-premium">
                            <label for="price">Price (PKR) *</label>
                            <input type="number" id="price" name="price" step="0.01" class="input-premium" placeholder="0.00" required>
                        </div>
                        <div class="form-group-premium">
                            <label for="stock">Initial Stock *</label>
                            <input type="number" id="stock" name="stock" value="0" class="input-premium" required>
                        </div>
                    </div>

                    <div class="form-group-premium">
                        <label for="category_id">Product Category *</label>
                        <select id="category_id" name="category_id" class="select-premium" required>
                            <option value="0">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small style="color: var(--text-muted); display: block; margin-top: 5px;">Choose the best category for visibility</small>
                    </div>

                    <div class="form-group-premium">
                        <label for="image_url">Product Image URL</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="image_url" name="image_url" class="input-premium" placeholder="https://images.unsplash.com/...">
                        </div>
                    </div>

                    <div style="margin-top: 2rem;">
                        <button type="submit" class="btn-premium" style="width: 100%;">
                            <i class="fas fa-plus-circle"></i> Publish Product
                        </button>
                    </div>
                </form>
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
    });
</script>
</body>
</html>
