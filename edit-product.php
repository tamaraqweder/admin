
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

// نجيب رقم المنتج من الرابط
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid product ID.");
}

// عند الضغط على Update
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    $sql = "UPDATE products
            SET name = ?, description = ?, price = ?, quantity = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssdii",
        $name,
        $description,
        $price,
        $quantity,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: products.php");
        exit;
    } else {
        $error = "Could not update product.";
        mysqli_stmt_close($stmt);
    }
}

// نجيب بيانات المنتج الحالية
$sql = "SELECT id, name, description, price, quantity
        FROM products
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$product) {
    exit("Product not found.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
</head>
<body>

    <h2>Edit Product</h2>

    <?php if (isset($error)) { ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form method="POST">

        <label>Product Name:</label>
        <input type="text" name="name"
               value="<?php echo htmlspecialchars($product["name"]); ?>"
               required>
        <br><br>

        <label>Description:</label>
        <textarea name="description" required><?php
            echo htmlspecialchars($product["description"]);
        ?></textarea>
        <br><br>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" min="0"
               value="<?php echo htmlspecialchars($product["price"]); ?>"
               required>
        <br><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" min="0"
               value="<?php echo htmlspecialchars($product["quantity"]); ?>"
               required>
        <br><br>

        <button type="submit">Update Product</button>

    </form>

    <br>
    <a href="products.php">Back to Products</a>

</body>
</html>
