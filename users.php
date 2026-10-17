
<?php

session_start();

require "db.php";

// نتأكد إن المستخدم سجل دخوله
if (!isset($_SESSION["user_id"])) {

    // إذا لم يسجل الدخول، نرجعه لصفحة Login
    header("Location: login.php");
    exit;

}

// نتأكد إن المستخدم Admin
if ($_SESSION["role"] !== "admin") {

    // إذا كان مستخدمًا عاديًا، نمنعه من دخول Dashboard
    header("Location: login.php");
    exit;

}

// نجيب جميع المستخدمين من قاعدة البيانات
$sql = "SELECT id, name, email FROM users";

$result = mysqli_query($conn, $sql);

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Users</title>
</head>
<body>

    <h2>All Registered Users</h2>

<a href="add-user.php">Add New User</a>

<br><br>

    <table border="1" cellpadding="10">
       <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Actions</th>
</tr>

        <?php while ($user = mysqli_fetch_assoc($result)) { ?>

          <tr>
    <td><?php echo htmlspecialchars($user["id"]); ?></td>

    <td><?php echo htmlspecialchars($user["name"]); ?></td>

    <td><?php echo htmlspecialchars($user["email"]); ?></td>

    
<td>
    <a href="edit-user.php?id=<?php echo $user["id"]; ?>">
        Edit
    </a>

    |

    <form action="delete-user.php" method="POST" style="display:inline;"
          onsubmit="return confirm('Are you sure you want to delete this user?');">

        <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">

        <button type="submit">Delete</button>

    </form>
</td>

</tr>

        <?php } ?>

    </table>

</body>
</html>

