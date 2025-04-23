<?php

// Logic for updating user settings can be placed here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example: Updating user profile
    if (isset($_POST['update_profile'])) {
        // Update profile logic goes here (e.g., update username, email, etc.)
        $new_username = $_POST['username'];
        
        // Assuming you have a users table
        $conn = new mysqli("localhost", "root", "", "inventory_db");
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $user_id = $_SESSION['user_id']; // Getting the logged-in user's ID

        $update_query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $new_username,  $user_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Profile updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to update profile.</div>";
        }

        $stmt->close();
        $conn->close();
    }

    // Logic for other settings can go here
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
        .sidebar { height: 100vh; background: #343a40; color: white; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover { background: #495057; color: white; }
        .form-card { border-radius: 12px; }
        .form-card input, .form-card button { width: 100%; }
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
            <a href="settings.php" class="fw-bold">Settings</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-light bg-white shadow-sm px-4">
                <span class="navbar-brand mb-0 h4">Settings</span>
            </nav>

            <div class="container mt-4">
                <!-- Profile Update Form -->
                <div class="card shadow-sm form-card p-4">
                    <h5 class="mb-3">Update Profile</h5>
                    <form method="POST" action="settings.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>" required>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>

                <!-- Logout Button -->
                <div class="mt-4">
                    <a href="settings.php?logout=true" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
