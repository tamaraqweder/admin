
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

// نتأكد أن الطلب جاء باستخدام POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: products.php");
    exit;
}

// نجيب رقم المنتج المراد حذفه
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: products.php");
    exit;
}

// نحذف المنتج من قاعدة البيانات
$sql = "DELETE FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// نرجع إلى صفحة المنتجات
header("Location: products.php");
exit;

?>
