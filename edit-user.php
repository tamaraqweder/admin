
<?php

session_start();

require "db.php";

// نتأكد أن المستخدم مسجل الدخول
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// نتأكد أن المستخدم Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

// نجيب رقم المستخدم من الرابط
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid user ID.");
}

// إذا تم إرسال الفورم
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    // نحدث اسم المستخدم وإيميله
    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $id);

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        header("Location: users.php");
        exit;

    } else {

        $error = "Could not update user. Check whether the email already exists.";
        mysqli_stmt_close($stmt);

    }
}

// نجيب بيانات المستخدم الحالية
$sql = "SELECT id, name, email FROM users WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$user) {
    exit("User not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
</head>

<body>

    <h2>Edit User</h2>

    <?php if (isset($error)) { ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form method="POST">

        <label>Name:</label>

        <input
            type="text"
            name="name"
            value="<?php echo htmlspecialchars($user["name"]); ?>"
            required
        >

        <br><br>

        <label>Email:</label>

        <input
            type="email"
            name="email"
            value="<?php echo htmlspecialchars($user["email"]); ?>"
            required
        >

        <br><br>

        <button type="submit">Update User</button>

    </form>

    <br>

    <a href="users.php">Back to Users</a>

</body>

</html>
