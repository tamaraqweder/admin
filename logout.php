
<?php

session_start();

// نحذف جميع بيانات الجلسة
$_SESSION = array();

// ننهي الجلسة
session_destroy();

// نرجع المستخدم إلى صفحة Login
header("Location: login.php");
exit;

?>
