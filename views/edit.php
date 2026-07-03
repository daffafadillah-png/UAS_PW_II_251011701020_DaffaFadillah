<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../models/DataModel.php';
if(!isset($_GET['nim'])) {
    header("Location: dashboard.php");
    exit();
}
$row = (new DataModel())->getByNim($_GET['nim']);
if(!$row) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='dashboard.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alumni | Satuan Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #F4F7FE; 
            color: #2B3674; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            padding: 20px; 
        }
        .form-card { 
            background: #FFFFFF; 
            border-radius: 24px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.02); 
            padding: 40px; 
            width: 100%; 
            max-width: 500px; 
        }
        .form-control, .form-select { 
            border-radius: 12px; 
            padding: 12px 15px; 
            border: 1px solid #E9EDF7; 
            background-color: #F8F9FA; 
            color: #2B3674; 
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }
        .form-control:focus, .form-select:focus {
            border-color: #FFB547;
            background-color: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(255, 181, 71, 0.15);
            color: #2B3674;
        }
        .btn-warning-custom { 
            background-color: #FFB547; 
            border: none; 
            border-radius: 12px; 
            font-weight: 600; 
            padding: 12px; 
            color: white; 
            transition: all 0.3s ease; 
        }
        .btn-warning-custom:hover { 
            background-color: #E69D30; 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(255, 181, 71, 0.3);
            color: white;
        }
        .btn-cancel {
            border-radius: 12px;
            padding: 12px;
            color: #A3AED1;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-cancel:hover {
            background-color: #F4F7FE;
            color: #2B3674;
        }
    </style>
</head>
<body>

    <div class="form-card">
        <h4 class="fw-bold mb-1">Edit Alumni</h4>
        <p class="text-muted small mb-4">Perbarui informasi data alumni terdaftar</p>
        
        <form action="../controllers/DataController.php?action=edit" method="POST">
            <input type="hidden" name="nim" value="<?= htmlspecialchars($row['nim']) ?>">
            
            <div class="mb-3">
                <label class="small fw-bold mb-1">NIM (Tidak Dapat Diubah)</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($row['nim']) ?>" disabled>
            </div>
            
            <div class="mb-3">
                <label class="small fw-bold mb-1">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama_lengkap']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="small fw-bold mb-1">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="small fw-bold mb-1">No. Telepon</label>
                <input type="text" name="no_telepon" class="form-control" value="<?= htmlspecialchars($row['no_telepon']) ?>" required>
            </div>
            
            <div class="mb-4">
                <label class="small fw-bold mb-1">Status Alumni</label>
                <select name="status" class="form-select" required>
                    <option value="Bekerja" <?= $row['status_alumni'] == 'Bekerja' ? 'selected' : '' ?>>Bekerja</option>
                    <option value="Mencari Kerja" <?= $row['status_alumni'] == 'Mencari Kerja' ? 'selected' : '' ?>>Mencari Kerja</option>
                    <option value="Lanjut Studi" <?= $row['status_alumni'] == 'Lanjut Studi' ? 'selected' : '' ?>>Lanjut Studi</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-warning-custom w-100 mb-2">Simpan Perubahan</button>
            <a href="dashboard.php" class="btn btn-light btn-cancel w-100 text-center text-decoration-none">Batal</a>
        </form>
    </div>

</body>
</html>