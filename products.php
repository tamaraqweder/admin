
<?php

session_start();

require "db.php";

// نتأكد أن المستخدم سجل دخوله
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// نتأكد أن المستخدم Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

// نجيب جميع المنتجات
$sql = "SELECT id, name, description, price, quantity, created_at
        FROM products";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
</head>

<body>

    <h2>All Products</h2>

    <!-- رابط إضافة منتج جديد -->
    <a href="add-product.php">Add New Product</a>

    <br><br>

    <table border="1" cellpadding="10">

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php while ($product = mysqli_fetch_assoc($result)) { ?>

            <tr>

                <td><?php echo htmlspecialchars($product["id"]); ?></td>

                <td><?php echo htmlspecialchars($product["name"]); ?></td>

                <td><?php echo htmlspecialchars($product["description"]); ?></td>

                <td><?php echo htmlspecialchars($product["price"]); ?></td>

                <td><?php echo htmlspecialchars($product["quantity"]); ?></td>

                <td><?php echo htmlspecialchars($product["created_at"]); ?></td>

                <td>
                    <!-- تعديل المنتج -->
                    <a href="edit-product.php?id=<?php echo $product["id"]; ?>">
                        Edit
                    </a>

                    |

                    <!-- حذف المنتج -->
                    <form action="delete-product.php" method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('Are you sure you want to delete this product?');">

                        <input type="hidden" name="id"
                               value="<?php echo $product["id"]; ?>">

                        <button type="submit">Delete</button>

                    </form>
                </td>

            </tr>

        <?php } ?>

    </table>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>
</html>
