<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once '../models/DataModel.php';
$model = new DataModel();
$nim_get = $_GET['nim'] ?? '';
$alumni = $model->getByNim($nim_get); 

if (!$alumni) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Alumni | Satuan Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #F4F7FE; 
            color: #2B3674; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            overflow-x: hidden;
        }
        @keyframes formPopIn {
            0% { opacity: 0; transform: scale(0.95) translateY(30px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .form-card-wrapper {
            background: #ffffff;
            border-radius: 24px;
            padding: 45px;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 20px 50px rgba(67, 24, 255, 0.04);
            border: none;
            position: relative;
            animation: formPopIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.2) both;
        }
        .form-card-wrapper::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px;
            background: linear-gradient(90deg, #4318FF 0%, #FFB800 100%); border-radius: 24px 24px 0 0;
        }
        .icon-header-box {
            width: 64px; height: 64px;
            background-color: #EEF2FF;
            color: #4318FF;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; margin: 0 auto 20px;
            transition: transform 0.3s ease;
        }
        .form-card-wrapper:hover .icon-header-box { transform: scale(1.1) rotate(-5deg); }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #2B3674; margin-bottom: 8px; }
        .form-control, .form-select {
            border-radius: 12px; padding: 12px 18px; border: 1px solid #E9EDF7;
            background: #F8F9FA; color: #2B3674; font-size: 0.9rem; transition: all 0.25s ease;
        }
        .form-control:focus, .form-select:focus {
            background: #ffffff; border-color: #4318FF;
            box-shadow: 0 8px 20px rgba(67, 24, 255, 0.06); transform: translateY(-2px);
        }
        .form-control[readonly] {
            background-color: #F1F4FA !important;
            color: #8F9BBA !important;
            border-color: #E9EDF7 !important;
            cursor: not-allowed;
        }
        .form-control[readonly]:focus {
            box-shadow: none !important;
            transform: none !important;
            border-color: #E9EDF7 !important;
        }
        .btn-save-custom {
            background-color: #4318FF; color: #ffffff; border-radius: 12px;
            font-weight: 700; padding: 12px 30px; border: none; transition: all 0.3s ease;
        }
        .btn-save-custom:hover {
            background-color: #3311CC; box-shadow: 0 10px 20px rgba(67, 24, 255, 0.25); transform: translateY(-2px);
        }
        .btn-cancel-custom {
            background-color: #F4F7FE; color: #A3AED0; border-radius: 12px;
            font-weight: 600; padding: 12px 30px; border: none; transition: all 0.3s ease;
            text-decoration: none; display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-cancel-custom:hover { background-color: #E2E8F5; color: #2B3674; transform: translateY(-2px); }

        @media (max-width: 576px) {
            body { padding: 20px 10px; }
            .form-card-wrapper { padding: 30px 20px; border-radius: 16px; }
            .d-flex.gap-3 { flex-direction: column-reverse; gap: 10px !important; }
            .btn-save-custom, .btn-cancel-custom { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="card form-card-wrapper">
        <div class="icon-header-box">
            <i class="bi bi-pencil-square"></i>
        </div>

        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Ubah Data Alumni</h3>
            <p class="text-muted small">Perbarui informasi data alumni secara berkala dengan benar</p>
        </div>

        <form action="../controllers/DataController.php?action=update" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">NIM (Nomor Induk Mahasiswa)</label>
                <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($alumni['nim']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($alumni['nama_lengkap'] ?? $alumni['nama'] ?? '') ?>" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Email Aktif</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($alumni['email']) ?>" placeholder="nama@email.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor Telepon / WhatsApp</label>
                <input type="text" name="no_telepon" class="form-control" value="<?= htmlspecialchars($alumni['no_telepon']) ?>" placeholder="Contoh: 08123456789" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status Alumni Saat Ini</label>
                <select name="status_alumni" class="form-select" required>
                    <option value="Bekerja" <?= ($alumni['status_alumni'] ?? $alumni['status'] == 'Bekerja') ? 'selected' : '' ?>>Bekerja</option>
                    <option value="Lanjut Studi" <?= ($alumni['status_alumni'] ?? $alumni['status'] == 'Lanjut Studi') ? 'selected' : '' ?>>Lanjut Studi</option>
                    <option value="Mencari Kerja" <?= ($alumni['status_alumni'] ?? $alumni['status'] == 'Mencari Kerja') ? 'selected' : '' ?>>Mencari Kerja</option>
                </select>
            </div>

            <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($alumni['foto'] ?? 'default.png') ?>">

            <div class="mb-4">
                <label class="form-label">Perbarui Pas Foto Resmi <span class="text-muted small fw-normal">(Biarkan kosong jika tidak ingin diganti)</span></label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <p class="text-muted small mt-1 mb-0" style="font-size: 0.75rem;">Foto saat ini: <strong class="text-dark"><?= htmlspecialchars($alumni['foto'] ?? 'default.png') ?></strong></p>
            </div>

            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="dashboard.php" class="btn btn-cancel-custom"><i class="bi bi-x-lg me-1"></i> Batal</a>
                <button type="submit" name="submit" class="btn btn-save-custom shadow-sm"><i class="bi bi-check-lg me-1"></i> Perbarui Data</button>
            </div>
        </form>
    </div>

</body>
</html>