<?php
require_once __DIR__ . '/../config/Database.php';

class DataModel extends Database {
    
    public function __construct() { 
        parent::__construct(); 
    }


    public function getAll() {
        $res = $this->conn->query("SELECT * FROM alumni ORDER BY tgl_daftar DESC");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    
    public function getByNim($nim) {
        $stmt = $this->conn->prepare("SELECT * FROM alumni WHERE nim = ?");
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    
    public function create($nim, $nama, $email, $status, $foto, $notelp) {
        $tgl_daftar = date('Y-m-d');
        
        $stmt = $this->conn->prepare("INSERT INTO alumni (nim, foto, nama_lengkap, email, no_telepon, status_alumni, tgl_daftar) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nim, $foto, $nama, $email, $notelp, $status, $tgl_daftar);
        return $stmt->execute();
    }

    
    public function update($nim, $nama, $email, $status, $foto, $notelp) {
        $stmt = $this->conn->prepare("UPDATE alumni SET nama_lengkap = ?, email = ?, status_alumni = ?, foto = ?, no_telepon = ? WHERE nim = ?");
        
        
        $stmt->bind_param("ssssss", $nama, $email, $status, $foto, $notelp, $nim);
        return $stmt->execute();
    }

    
    public function delete($nim) {
        $stmt = $this->conn->prepare("DELETE FROM alumni WHERE nim = ?");
        $stmt->bind_param("s", $nim);
        return $return = $stmt->execute();
    }
}
?>