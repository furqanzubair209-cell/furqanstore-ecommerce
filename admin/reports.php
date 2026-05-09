<?php
require_once('../config/db.php');

if (!isRole('admin')) {
    redirect('index.php');
}

$conn = getConnection();

// 1. Using a VIEW (Joins + View)
$product_details = $conn->query("SELECT * FROM v_product_details LIMIT 10")->fetch_all(MYSQLI_ASSOC);

// 2. Using Group By & Aggregates with Having (from a View)
$vendor_performance = $conn->query("SELECT * FROM v_vendor_performance")->fetch_all(MYSQLI_ASSOC);

// 3. Using a Subquery directly in PHP
$top_products = $conn->query("
    SELECT name, price 
    FROM products 
    WHERE id IN (
        SELECT product_id 
        FROM order_items 
        GROUP BY product_id 
        ORDER BY SUM(quantity) DESC
    ) LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// 4. Using a Stored Procedure
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("CALL sp_get_user_orders(?)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$my_orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Reports - FurqanStore</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Advanced SQL Reports</h1>
        
        <section>
            <h2>Vendor Performance (Group By, Aggregates, Having)</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Vendor Name</th>
                        <th>Units Sold</th>
                        <th>Total Earnings</th>
                        <th>Avg Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendor_performance as $row): ?>
                    <tr>
                        <td><?= $row['vendor_name'] ?></td>
                        <td><?= $row['total_units_sold'] ?></td>
                        <td>$<?= number_format($row['total_earnings'], 2) ?></td>
                        <td>$<?= number_format($row['average_unit_price'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Product Details (Views & Joins)</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Vendor</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($product_details as $row): ?>
                    <tr>
                        <td><?= $row['product_name'] ?></td>
                        <td><?= $row['category_name'] ?></td>
                        <td><?= $row['vendor_name'] ?></td>
                        <td>$<?= $row['price'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Top Products (Subquery)</h2>
            <ul>
                <?php foreach ($top_products as $row): ?>
                <li><?= $row['name'] ?> - $<?= $row['price'] ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
</body>
</html>
