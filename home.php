<?php 
session_start();
include "db_conn.php";

if (isset($_SESSION['id']) && isset($_SESSION['user_name']))

// Get total products
$totalProducts = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];

// Get total sales (transactions)
$totalSales = $conn->query("SELECT COUNT(*) as total FROM sales")->fetch_assoc()['total'];

// Get low stock products
$lowStock = $conn->query("SELECT * FROM products WHERE quantity <= 5 ORDER BY quantity ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background: #343a40;
            color: white;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }
        .sidebar a:hover {
            background: #495057;
            color: white;
        }
        .dashboard-card {
            border-radius: 12px;
            padding: 20px;
            color: white;
        }
        .card-products {
            background-color: #0d6efd;
        }
        .card-sales {
            background-color: #198754;
        }
        .card-low {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h4 class="text-white mb-4">POS System</h4>
            <a href="home.php" class="fw-bold">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="sales.php">Sales</a>
            <a href="ingredients.php">Ingredients</a>
            <a href="reports.php">Reports</a>
            <a href="settings.php">Settings</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-light bg-white shadow-sm px-4">
                <span class="navbar-brand mb-0 h4">Dashboard</span>
            </nav>

            <div class="container mt-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="dashboard-card card-products shadow">
                            <h5>Total Products</h5>
                            <h2><?= $totalProducts ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card card-sales shadow">
                            <h5>Total Sales</h5>
                            <h2><?= $totalSales ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card card-low shadow">
                            <h5>Low Stock Items</h5>
                            <h2><?= $lowStock->num_rows ?></h2>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Items Table -->
                <div class="card mt-5 shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">Low Stock Products (≤ 5)</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $lowStock->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= $row['quantity'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php if ($lowStock->num_rows === 0): ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No low stock items.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
