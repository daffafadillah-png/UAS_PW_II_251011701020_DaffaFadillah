<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alumni | Satuan Alumni</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #EAF2FD 0%, #F3F7FD 100%); 
            color: #0F172A; 
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .card-form-wrapper {
            background: #FFFFFF; 
            border-radius: 24px; 
            border: none;
            box-shadow: 0 20px 40px -15px rgba(15, 30, 54, 0.08);
            padding: 40px; 
            position: relative; 
            overflow: hidden;
            width: 100%;
            max-width: 540px;
        }
        
        .card-form-wrapper::before {
            content: ''; 
            position: absolute; 
            top: 0; left: 0; right: 0; 
            height: 6px;
            background: linear-gradient(90deg, #1D4ED8 0%, #EAB308 100%);
        }
        
        /* Form Controls */
        .form-label { font-weight: 600; font-size: 0.85rem; color: #1E293B; margin-bottom: 6px; }
        .form-control, .form-select {
            border-radius: 12px; padding: 12px 16px; border: 1px solid #E2E8F0;
            background-color: #F8FAFC; color: #334155; font-weight: 500; font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
        }
        .form-control:focus, .form-select:focus {
            border-color: #1D4ED8; background-color: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.08); color: #0F172A;
        }

        /* Buttons */
        .btn-blue-custom { 
            background: #1D4ED8; color: white; border-radius: 12px; font-weight: 600; 
            padding: 14px; border: none; transition: 0.2s; 
        }
        .btn-blue-custom:hover { background: #1E40AF; color: white; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(29, 78, 216, 0.15); }
        
        .btn-cancel {
            border-radius: 12px; padding: 14px; color: #64748B; font-weight: 600;
            transition: all 0.2s ease; background-color: #F1F5F9; text-decoration: none; display: block;
        }
        .btn-cancel:hover { background-color: #E2E8F0; color: #0F172A; }
    </style>
</head>
<body>

    <div class="card card-form-wrapper">
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 p-3 rounded-circle mb-3">
                <i class="bi bi-person-plus-fill text-warning fs-2"></i>
            </div>
            <h4 class="fw-bold mb-1" style="letter-spacing: -0.5px; color: #0F1E36;">Tambah Alumni Baru</h4>
            <p class="text-muted small mb-0">Masukkan informasi mahasiswa secara lengkap dan valid</p>
        </div>

        <form action="../controllers/DataController.php?action=tambah" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label class="form-label">NIM (Nomor Induk Mahasiswa)</label>
                <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM alumni" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap beserta gelar" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Alamat Email Aktif</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Nomor Telepon / WhatsApp</label>
                <input type="text" name="no_telepon" class="form-control" placeholder="Contoh: 08123456789" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Status Alumni Saat Ini</label>
                <select name="status" class="form-select" required>
                    <option value="" disabled selected>Pilih Status Terkini...</option>
                    <option value="Bekerja">Bekerja</option>
                    <option value="Mencari Kerja">Mencari Kerja</option>
                    <option value="Lanjut Studi">Lanjut Studi</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Unggah Pas Foto Resmi</label>
                <input type="file" name="foto" class="form-control bg-white" required>
            </div>
            
            <div class="row g-2 mt-2">
                <div class="col-8">
                    <button type="submit" class="btn btn-blue-custom w-100"><i class="bi bi-check-circle-fill me-1"></i> Simpan Data</button>
                </div>
                <div class="col-4">
                    <a href="dashboard.php" class="btn-cancel text-center">Batal</a>
                </div>
            </div>
        </form>
    </div>

</body>
</html>