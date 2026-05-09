<?php
require_once(__DIR__ . '/../config/db.php');
$conn = getConnection();

// Fetch reports from views
$product_details = $conn->query("SELECT * FROM v_product_details LIMIT 10")->fetch_all(MYSQLI_ASSOC);
$vendor_performance = $conn->query("SELECT * FROM v_vendor_performance")->fetch_all(MYSQLI_ASSOC);
$order_summary = $conn->query("SELECT * FROM v_order_summary LIMIT 10")->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Enhancements Demo - Reports</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f7f6; }
        h1 { color: #333; }
        section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #6366f1; color: white; }
    </style>
</head>
<body>
    <h1>FurqanStore - Advanced SQL Reports</h1>

    <section>
        <h2>Vendor Performance (Aggregates & Group By)</h2>
        <table>
            <thead>
                <tr>
                    <th>Vendor</th>
                    <th>Units Sold</th>
                    <th>Total Earnings</th>
                    <th>Avg Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($vendor_performance as $row): ?>
                <tr>
                    <td><?= $row['vendor_name'] ?></td>
                    <td><?= $row['total_units_sold'] ?></td>
                    <td>PKR <?= number_format($row['total_earnings']) ?></td>
                    <td>PKR <?= number_format($row['average_unit_price']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Order Summary (Views & Aggregates)</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total Items</th>
                    <th>Subtotal</th>
                    <th>Tax (5%)</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($order_summary as $row): ?>
                <tr>
                    <td>#<?= $row['order_id'] ?></td>
                    <td><?= $row['customer_name'] ?></td>
                    <td><?= $row['total_items'] ?></td>
                    <td>PKR <?= number_format($row['total']) ?></td>
                    <td>PKR <?= number_format($row['total'] * 0.05) ?></td>
                    <td>PKR <?= number_format($row['total'] * 1.05) ?></td>
                    <td><span style="color: <?= $row['status'] === 'completed' ? 'green' : 'orange' ?>"><?= ucfirst($row['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Product Details (Views & Joins)</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Vendor</th>
                    <th>Price</th>
                    <th>Tax (5%)</th>
                    <th>Total</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($product_details as $row): ?>
                <tr>
                    <td><?= $row['product_name'] ?></td>
                    <td><?= $row['category_name'] ?></td>
                    <td><?= $row['vendor_name'] ?></td>
                    <td>PKR <?= number_format($row['price']) ?></td>
                    <td>PKR <?= number_format($row['price'] * 0.05) ?></td>
                    <td>PKR <?= number_format($row['price'] * 1.05) ?></td>
                    <td><?= $row['stock'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <a href="apply_sql.php">Re-apply SQL Enhancements</a>
</body>
</html>
