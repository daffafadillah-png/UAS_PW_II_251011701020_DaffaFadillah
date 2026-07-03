<?php
session_start();
require_once '../models/DataModel.php';

$model = new DataModel();
$action = $_GET['action'] ?? '';

if ($action == 'tambah') {
    $nama_file  = $_FILES['foto']['name'] ?? '';
    $tmp_foto   = $_FILES['foto']['tmp_name'] ?? '';
    $error_foto = $_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE;
    $path_folder = "../uploads/";
    
    
    if (!is_dir($path_folder)) {
        mkdir($path_folder, 0777, true);
    }

    
    if ($error_foto === UPLOAD_ERR_OK && !empty($nama_file)) {
        $ekstensi  = pathinfo($nama_file, PATHINFO_EXTENSION);
        $foto_baru = time() . "_" . $_POST['nim'] . "." . $ekstensi;
        $target_file = $path_folder . $foto_baru;
        
        
        if (move_uploaded_file($tmp_foto, $target_file)) {
            $nama_foto_db = $foto_baru;
        } else {
            $nama_foto_db = 'default.png'; 
        }
    } else {
        $nama_foto_db = 'default.png'; 
    }

    
    $simpan = $model->create(
        $_POST['nim'], 
        $_POST['nama'], 
        $_POST['email'], 
        $_POST['status'], 
        $nama_foto_db, 
        $_POST['no_telepon']
    );
    
    if ($simpan) {
        echo "<script>alert('Data Berhasil Disimpan!'); window.location='../views/dashboard.php';</script>";
    } else {
        echo "<script>alert('GAGAL: Terjadi kesalahan pada query database!'); window.history.back();</script>";
    }

} elseif ($action == 'edit') {
    $update = $model->update($_POST['nim'], $_POST['nama'], $_POST['email'], $_POST['status']);
    
    if ($update) {
        echo "<script>alert('Data Berhasil Diperbarui!'); window.location='../views/dashboard.php';</script>";
    } else {
        echo "<script>alert('GAGAL: Gagal memperbarui data di database!'); window.history.back();</script>";
    }

} elseif ($action == 'delete') {
    $model->delete($_GET['nim']);
    header("Location: ../views/dashboard.php");
    exit();
}
?>