<?php
include "db_conn.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity_sold = $_POST['quantity_sold'];

    // 1. Insert into sales
    $conn->query("INSERT INTO sales (product_id, quantity_sold) VALUES ($product_id, $quantity_sold)");

    // 2. Reduce product stock
    $conn->query("UPDATE products SET quantity = quantity - $quantity_sold WHERE id = $product_id");

    // 3. Fetch and reduce ingredient stocks
    $ingredients = $conn->query("
        SELECT ingredient_id, quantity_required 
        FROM product_ingredients 
        WHERE product_id = $product_id
    ");

    while ($row = $ingredients->fetch_assoc()) {
        $ingredient_id = $row['ingredient_id'];
        $qty_required = $row['quantity_required'];
        $deduct = $qty_required * $quantity_sold;

        $conn->query("UPDATE ingredients SET quantity = quantity - $deduct WHERE id = $ingredient_id");
    }

    $message = "Sale recorded and stocks updated!";
}

// Get all products for the dropdown
$products = $conn->query("SELECT id, name, quantity FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales - POS System</title>
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
        .form-card {
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h4 class="text-white mb-4">POS System</h4>
            <a href="home.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="sales.php" class="fw-bold">Sales</a>
            <a href="ingredients.php">Ingredients</a>
            <a href="reports.php">Reports</a>
            <a href="settings.php">Settings</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Top Navbar -->
            <nav class="navbar navbar-light bg-white shadow-sm px-4">
                <span class="navbar-brand mb-0 h4">Sales</span>
            </nav>

            <!-- Sale Form -->
            <div class="container mt-4">
                <div class="card shadow-sm form-card p-4">
                    <h5 class="mb-3">Record a New Sale</h5>

                    <?php if ($message): ?>
                        <div class="alert alert-success"><?= $message ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Select Product</label>
                            <select name="product_id" id="product_id" class="form-select" required>
                                <option value="">-- Choose Product --</option>
                                <?php while ($row = $products->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>">
                                        <?= htmlspecialchars($row['name']) ?> (Stock: <?= $row['quantity'] ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quantity_sold" class="form-label">Quantity Sold</label>
                            <input type="number" name="quantity_sold" id="quantity_sold" class="form-control" required min="1">
                        </div>

                        <button type="submit" class="btn btn-success">Submit Sale</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>