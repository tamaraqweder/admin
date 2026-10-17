
<?php

session_start();

require "db.php";

// نتأكد أن المستخدم سجل دخوله
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// نتأكد أن المستخدم مدير
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo "Access Denied! You are not an admin.";
    exit;
}

// حساب عدد المستخدمين
$usersQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$usersData = mysqli_fetch_assoc($usersQuery);
$usersCount = $usersData["total"];

// حساب عدد المنتجات
$productsQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$productsData = mysqli_fetch_assoc($productsQuery);
$productsCount = $productsData["total"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body>

    <h1>Admin Dashboard</h1>

    <p>Welcome, Admin!</p>

    <hr>

    <!-- Dashboard Statistics -->
    <h2>Dashboard Statistics</h2>

    <div>
        <h3>Total Users</h3>
        <p><?php echo $usersCount; ?></p>
    </div>

    <div>
        <h3>Total Products</h3>
        <p><?php echo $productsCount; ?></p>
    </div>

    <hr>

    <!-- User Management -->
    <h2>User Management</h2>
    <p>Manage registered users.</p>

    <a href="users.php">View Users</a>

    <br><br>

    <a href="add-user.php">Add New User</a>

    <hr>

    <!-- Product Management -->
    <h2>Product Management</h2>
    <p>Manage products.</p>

    <a href="products.php">View Products</a>

    <br><br>

    <a href="add-product.php">Add New Product</a>

    <hr>

    <!-- Logout -->
    <a href="logout.php">Logout</a>

</body>

</html>
