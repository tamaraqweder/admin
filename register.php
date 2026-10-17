
<?php

require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. نستقبل البيانات
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // 2. نجهز طلب البحث عن الإيميل
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id FROM users WHERE email = ?"
    );

    // 3. نربط الإيميل بطلب البحث
    mysqli_stmt_bind_param($stmt, "s", $email);

    // 4. ننفذ البحث
    mysqli_stmt_execute($stmt);

    // 5. نستلم النتيجة
    $result = mysqli_stmt_get_result($stmt);

    // 6. نعدّ النتائج
    $count = mysqli_num_rows($result);

    // 7. نفحص إذا الإيميل موجود
    if ($count > 0) 
        {

        echo "Email already exists";

        } else
           {

        // 8. نجهز كلمة المرور للتخزين الآمن
        $hashedPassword = password_hash(  $password,  PASSWORD_DEFAULT);

        // 9. نجهز طلب حفظ المستخدم
        $sql = "INSERT INTO users (name, email, password)
                VALUES (?, ?, ?)";

        $insertStmt = mysqli_prepare($conn, $sql);

        // 10. نربط البيانات بطلب الحفظ
        mysqli_stmt_bind_param(
            $insertStmt,
            "sss",
            $name,
            $email,
            $hashedPassword
        );

        // 11. ننفذ عملية الحفظ
        if (mysqli_stmt_execute($insertStmt)) {

            echo "Registration successful!";

        } else {

            echo "Registration failed!";

        }

        // 12. نغلق استعلام الحفظ
        mysqli_stmt_close($insertStmt);
    }

    // 13. نغلق استعلام البحث
    mysqli_stmt_close($stmt);
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>

    <h2>Create Account</h2>

    <form method="POST" action="">

        <label>Full Name:</label>
        <input type="text" name="name" required>
        <br><br>

        <label>Email:</label>
        <input type="email" name="email" required>
        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit" name="register">Register</button>

    </form>

</body>
</html>
