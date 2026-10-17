<?php
//ي دالة بتعمل اتصال بين PHP وقاعدة البيانات باستخدام MySQLi.
$conn = mysqli_connect(
    "localhost", 
    "root",
    "",
    "registration_system"
);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}




?>