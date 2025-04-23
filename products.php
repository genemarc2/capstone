<?php
include "db_conn.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $conn->query("INSERT INTO products (name, price) VALUES ('$name', $price)");
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $conn->query("UPDATE products SET name='$name', price=$price WHERE id=$id");
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    $conn->query("DELETE FROM product_ingredients WHERE product_id=$id");
}

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - POS System</title>
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
            <a href="products.php" class="fw-bold">Products</a>
            <a href="sales.php">Sales</a>
            <a href="ingredients.php">Ingredients</a>
            <a href="reports.php">Reports</a>
            <a href="settings.php">Settings</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-light bg-white shadow-sm px-4">
                <span class="navbar-brand mb-0 h4">Products</span>
            </nav>

            <div class="container mt-4">
                <!-- Add Product -->
                <div class="card shadow-sm form-card p-4 mb-4">
                    <h5 class="mb-3">Add New Product</h5>
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Product Name" required>
                            </div>
                            <div class="col-md-4">
                                <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
                            </div>
                            <div class="col-md-2">
                                <button name="add" class="btn btn-primary w-100">Add</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Products Table -->
                <div class="card shadow-sm form-card p-4">
                    <h5 class="mb-3">Product List</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price (₱)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $products->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= number_format($row['price'], 2) ?></td>
                                    <td>
                                        <form method="POST" class="d-inline-block">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control d-inline-block" style="width: 150px;" required>
                                            <input type="number" name="price" step="0.01" value="<?= $row['price'] ?>" class="form-control d-inline-block" style="width: 100px;" required>
                                            <button name="update" class="btn btn-sm btn-warning">Update</button>
                                        </form>
                                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if ($products->num_rows === 0): ?>
                                <tr><td colspan="4" class="text-center">No products found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
