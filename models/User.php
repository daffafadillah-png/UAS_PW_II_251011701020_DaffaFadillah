<?php
require_once __DIR__ . '/../config/Database.php';

class User extends Database {
    public function __construct() { parent::__construct(); }

    public function register($nama, $username, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (nama_lengkap, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $username, $hash);
        return $stmt->execute();
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }
}
?>