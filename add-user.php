
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

// معالجة الفورم عند الضغط على Add User
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // استقبال البيانات
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // تشفير كلمة المرور
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // إضافة المستخدم إلى قاعدة البيانات
    $sql = "INSERT INTO users (name, email, password, role)
            VALUES (?, ?, ?, 'user')";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $name,
        $email,
        $hashedPassword
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: users.php");
        exit;
    } else {
        $error = "Could not add user. The email may already exist.";
    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
</head>
<body>

    <h2>Add New User</h2>

    <?php if (isset($error)) { ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form method="POST">

        <label>Name:</label>
        <input type="text" name="name" required>

        <br><br>

        <label>Email:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required minlength="8">

        <br><br>

        <button type="submit">Add User</button>

    </form>

    <br>

    <a href="users.php">Back to Users</a>

</body>
</html>
