
<?php

session_start();

require "db.php";

// نتأكد أن الفورم تم إرساله باستخدام POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // نستقبل البيانات من الفورم
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // نبحث عن المستخدم باستخدام الإيميل
    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);

    // نربط الإيميل بمكان علامة الاستفهام
    mysqli_stmt_bind_param($stmt, "s", $email);

    // ننفذ البحث
    mysqli_stmt_execute($stmt);

    // نستقبل نتيجة البحث
    $result = mysqli_stmt_get_result($stmt);

    // نجيب بيانات المستخدم إذا كان موجودًا
    $user = mysqli_fetch_assoc($result);

    if ($user) {

        // نتحقق من كلمة المرور
        if (password_verify($password, $user["password"])) {

            // نجدد معرّف الجلسة بعد نجاح تسجيل الدخول
            session_regenerate_id(true);

            // نحفظ هوية المستخدم وصلاحيته داخل الجلسة
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];

            // إذا كان المستخدم مديرًا
            if ($user["role"] === "admin") {

                // ننقله إلى لوحة التحكم
                header("Location: dashboard.php");
                exit;

            } else {

                // ننقل المستخدم العادي إلى صفحة المستخدمين
                header("Location: users.php");
                exit;

            }

        } else {

            echo "Wrong password!";

        }

    } else {

        echo "Email not found!";

    }

    // نغلق الاستعلام
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>

<body>

    <h2>Login</h2>

    <form method="POST" action="">

        <label>Email:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit" name="login">Login</button>

    </form>

</body>

</html>
