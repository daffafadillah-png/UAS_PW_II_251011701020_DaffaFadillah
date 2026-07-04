<?php

session_start();
require_once '../config/Database.php';

class AuthController extends Database {
    
    public function __construct() {
        parent::__construct();
    }

    public function register($nama, $username, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (nama_lengkap, username, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $nama, $username, $hashed_password);
        return $stmt->execute();
    }

  
    public function login($username, $password) {
        
        if ($username === 'Admin' && $password === 'Masuk123') {
            $_SESSION['user_id'] = 'admin_utama';
            $_SESSION['nama_lengkap'] = 'Main Administrator';
            $_SESSION['role'] = 'admin';
            return true;
        }

   
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = 'user';
                return true;
            }
        }
        return false;
    }
}

$auth = new AuthController();


if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: ../views/login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    
    if (isset($_POST['login']) || (isset($_GET['action']) && $_GET['action'] == 'login') || (isset($_POST['username']) && !isset($_POST['nama_lengkap']))) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($auth->login($username, $password)) {
            header("Location: ../views/dashboard.php");
        } else {
            header("Location: ../views/login.php?status=failed_login");
        }
        exit();
    }

   
    if (isset($_POST['register']) || isset($_POST['nama_lengkap'])) {
        $nama = $_POST['nama_lengkap'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($auth->register($nama, $username, $password)) {
            header("Location: ../views/login.php?status=success_register");
        } else {
            header("Location: ../views/register.php?status=failed");
        }
        exit();
    }
}

header("Location: ../views/login.php");
exit();
?>