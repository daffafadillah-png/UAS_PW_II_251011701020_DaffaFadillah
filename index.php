<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: views/login.php");
} else {
    header("Location: views/dashboard.php");
}
exit();
?>