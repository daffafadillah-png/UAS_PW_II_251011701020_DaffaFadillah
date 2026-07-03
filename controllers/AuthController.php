<?php
session_start();
require_once '../models/User.php';

$userModel = new User();
$action = $_GET['action'] ?? '';

if ($action == 'register') {
    if ($userModel->register($_POST['nama_lengkap'], $_POST['username'], $_POST['password'])) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='../views/login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal atau username sudah ada!'); window.history.back();</script>";
    }
} elseif ($action == 'login') {
    $user = $userModel->login($_POST['username'], $_POST['password']);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        header("Location: ../views/dashboard.php");
    } else {
        echo "<script>alert('Username atau password salah!'); window.history.back();</script>";
    }
} elseif ($action == 'logout') {
    session_destroy();
    header("Location: ../views/login.php");
}
?>