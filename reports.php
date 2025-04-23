<?php
include "db_conn.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sales by Product: Calculate total sales for each product
$sales_by_product_query = "
SELECT p.name AS product_name, SUM(s.quantity_sold) AS total_sold, p.price, SUM(s.quantity_sold * p.price) AS total_sales
FROM sales s
JOIN products p ON s.product_id = p.id
GROUP BY p.name
ORDER BY total_sales DESC
";
$sales_by_product_result = $conn->query($sales_by_product_query);

// Ingredient Usage Report: Calculate total usage of ingredients
$ingredient_usage_query = "
SELECT i.name AS ingredient_name, SUM(pi.quantity_required * s.quantity_sold) AS total_used
FROM sales s
JOIN product_ingredients pi ON s.product_id = pi.product_id
JOIN ingredients i ON pi.ingredient_id = i.id
GROUP BY i.name
ORDER BY total_used DESC
";
$ingredient_usage_result = $conn->query($ingredient_usage_query);

// Low Stock Products: Products with stock less than 5
$low_stock_query = "
SELECT p.name, p.stock
FROM products p
WHERE p.stock < 5
ORDER BY p.stock ASC
";
$low_stock_result = $conn->query($low_stock_query);

// Check if query executed properly
if (!$low_stock_result) {
    die("Error in low stock query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detailed Reports - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
        .sidebar { height: 100vh; background: #343a40; color: white; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover { background: #495057; color: white; }
        .form-card { border-radius: 12px; }
        .report-card { margin-bottom: 30px; }
        .report-card table { width: 100%; }
        .report-card th, .report-card td { text-align: left; padding: 10px; }
        .report-card th { background-color: #343a40; color: white; }
        .report-card tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h4 class="text-white mb-4">POS System</h4>
            <a href="home.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="sales.php">Sales</a>
            <a href="ingredients.php">Ingredients</a>
            <a href="reports.php">Reports</a>
            <a href="detailed_reports.php" class="fw-bold">Detailed Reports</a>
            <a href="settings.php">Settings</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-light bg-white shadow-sm px-4">
                <span class="navbar-brand mb-0 h4">Detailed Reports</span>
            </nav>

            <div class="container mt-4">
                <!-- Sales by Product Report -->
                <div class="card shadow-sm form-card report-card p-4">
                    <h5 class="mb-3">Sales by Product</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Product</th>
                                <th>Quantity Sold</th>
                                <th>Price (₱)</th>
                                <th>Total Sales (₱)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $sales_by_product_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                                    <td><?= $row['total_sold'] ?></td>
                                    <td><?= number_format($row['price'], 2) ?></td>
                                    <td><?= number_format($row['total_sales'], 2) ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if ($sales_by_product_result->num_rows === 0): ?>
                                <tr><td colspan="4" class="text-center">No sales data found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Ingredient Usage Report -->
                <div class="card shadow-sm form-card report-card p-4">
                    <h5 class="mb-3">Ingredient Usage</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Ingredient</th>
                                <th>Total Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $ingredient_usage_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['ingredient_name']) ?></td>
                                    <td><?= $row['total_used'] ?> units</td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if ($ingredient_usage_result->num_rows === 0): ?>
                                <tr><td colspan="2" class="text-center">No ingredient usage data found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Low Stock Report -->
                <div class="card shadow-sm form-card report-card p-4">
                    <h5 class="mb-3">Low Stock Products</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Product</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($low_stock_result->num_rows > 0): ?>
                                <?php while ($row = $low_stock_result->fetch_assoc()): ?>
                                    <tr class="text-danger">
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= $row['stock'] ?> units</td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="text-center">No low stock products.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
