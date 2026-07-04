<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../models/DataModel.php';
$model = new DataModel();

$action = $_GET['action'] ?? '';


if ($action == 'store' || $action == 'tambah') {
    
    $nim           = $_POST['nim'] ?? '';
    $nama_lengkap  = $_POST['nama_lengkap'] ?? $_POST['nama'] ?? '';
    $email         = $_POST['email'] ?? '';
    $no_telepon    = $_POST['no_telepon'] ?? '';
    $status_alumni = $_POST['status_alumni'] ?? $_POST['status'] ?? '';
    
    $nama_file   = $_FILES['foto']['name'] ?? '';
    $tmp_foto    = $_FILES['foto']['tmp_name'] ?? '';
    $error_foto  = $_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE;
    $path_folder = "../uploads/";
    
    if (!is_dir($path_folder)) {
        mkdir($path_folder, 0777, true);
    }

    if ($error_foto === UPLOAD_ERR_OK && !empty($nama_file)) {
        $ekstensi    = pathinfo($nama_file, PATHINFO_EXTENSION);
        $foto_baru   = time() . "_" . $nim . "." . $ekstensi;
        $target_file = $path_folder . $foto_baru;
        
        if (move_uploaded_file($tmp_foto, $target_file)) {
            $nama_foto_db = $foto_baru;
        } else {
            $nama_foto_db = 'default.png'; 
        }
    } else {
        $nama_foto_db = 'default.png'; 
    }

    $simpan = $model->create($nim, $nama_lengkap, $email, $status_alumni, $nama_foto_db, $no_telepon);
    
    if ($simpan) {
        header("Location: ../views/dashboard.php?status=success_add");
    } else {
        header("Location: ../views/dashboard.php?status=failed_add");
    }
    exit();


} elseif ($action == 'edit' || $action == 'update') {
    
    $nim           = $_POST['nim'] ?? '';
    $nama_lengkap  = $_POST['nama_lengkap'] ?? $_POST['nama'] ?? '';
    $email         = $_POST['email'] ?? '';
    $no_telepon    = $_POST['no_telepon'] ?? '';
    $status_alumni = $_POST['status_alumni'] ?? $_POST['status'] ?? '';
    
    $nama_file = $_FILES['foto']['name'] ?? '';
    if (!empty($nama_file)) {
        $tmp_foto    = $_FILES['foto']['tmp_name'] ?? '';
        $path_folder = "../uploads/";
        $ekstensi    = pathinfo($nama_file, PATHINFO_EXTENSION);
        $foto_baru   = time() . "_" . $nim . "." . $ekstensi;
        
        if (move_uploaded_file($tmp_foto, $path_folder . $foto_baru)) {
            $nama_foto_db = $foto_baru;
        } else {
            $nama_foto_db = $_POST['foto_lama'] ?? 'default.png';
        }
    } else {
        $nama_foto_db = $_POST['foto_lama'] ?? 'default.png';
    }

    $update = $model->update($nim, $nama_lengkap, $email, $status_alumni, $nama_foto_db, $no_telepon);
    
    if ($update) {
        header("Location: ../views/dashboard.php?status=success_update");
    } else {
        header("Location: ../views/dashboard.php?status=failed_update");
    }
    exit();

} elseif ($action == 'delete') {
    $nim_hapus = $_GET['nim'] ?? $_GET['id'] ?? '';
    
    if (!empty($nim_hapus)) {
        $model->delete($nim_hapus);
        header("Location: ../views/dashboard.php?status=success_delete");
    } else {
        header("Location: ../views/dashboard.php?status=failed_delete");
    }
    exit();
}