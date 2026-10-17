
<?php

session_start();

require "db.php";

// نتأكد أن المستخدم Admin
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // نستقبل بيانات المنتج
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    // نضيف المنتج إلى قاعدة البيانات
    $sql = "INSERT INTO products (name, description, price, quantity)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssdi",
        $name,
        $description,
        $price,
        $quantity
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: products.php");
        exit;
    } else {
        $error = "Could not add product.";
        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>

    <h2>Add New Product</h2>

    <?php if (isset($error)) { ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form method="POST">

        <label>Product Name:</label>
        <input type="text" name="name" required>
        <br><br>

        <label>Description:</label>
        <textarea name="description" required></textarea>
        <br><br>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" min="0" required>
        <br><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" min="0" required>
        <br><br>

        <button type="submit">Add Product</button>

    </form>

    <br>

    <a href="products.php">Back to Products</a>

</body>
</html>
