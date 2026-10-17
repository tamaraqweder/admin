
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
    header("Location: users.php");
    exit;
}

// نجيب رقم المستخدم المراد حذفه
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: users.php");
    exit;
}

// نحذف المستخدم باستخدام Prepared Statement
$sql = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// نرجع لجدول المستخدمين
header("Location: users.php");
exit;

?>
