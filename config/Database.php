<?php
class Database {
    protected $conn;
    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "db_uas_251011701020");
        if ($this->conn->connect_error) {
            die("Koneksi ke database gagal: " . $this->conn->connect_error);
        }
    }
}
?>