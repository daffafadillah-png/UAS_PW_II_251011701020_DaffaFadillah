<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../models/DataModel.php';
$data = (new DataModel())->getAll();
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Alumni | Satuan Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #F4F7FE; 
            color: #2B3674; 
            margin: 0;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .anim-sidebar { animation: fadeInLeft 0.6s cubic-bezier(0.25, 1, 0.5, 1) both; }
        .anim-header { animation: fadeInUp 0.6s cubic-bezier(0.25, 1, 0.5, 1) both; animation-delay: 0.1s; }
        .anim-title { animation: fadeInUp 0.6s cubic-bezier(0.25, 1, 0.5, 1) both; animation-delay: 0.2s; }
        .anim-table { animation: fadeInUp 0.6s cubic-bezier(0.25, 1, 0.5, 1) both; animation-delay: 0.3s; }

        .sidebar-wrapper {
            width: 280px;
            background-color: #0B1931;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 30px 20px;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 100;
        }
        .sidebar-brand { 
            display: flex; align-items: center; gap: 12px; margin-bottom: 40px; padding-left: 10px; 
        }
        .sidebar-brand i { font-size: 1.8rem; color: #FFB800; }
        .sidebar-brand-text { font-weight: 700; font-size: 1.1rem; letter-spacing: 0.5px; }
        .sidebar-brand-sub { font-size: 0.7rem; color: #A3AED0; opacity: 0.8; }
        
        .sidebar-menu { display: flex; flex-direction: column; gap: 8px; flex-grow: 1; }
        .nav-link-custom {
            display: flex; align-items: center; gap: 15px; padding: 14px 20px; color: #A3AED0;
            text-decoration: none; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;
        }
        .nav-link-custom:hover { color: #ffffff; background: rgba(255,255,255,0.05); transform: translateX(4px); }
        .nav-link-custom.active { background-color: #4318FF; color: #ffffff; box-shadow: 0 10px 20px rgba(67, 24, 255, 0.25); }
        
        .logout-link {
            display: flex; align-items: center; gap: 15px; padding: 14px 20px; color: #FF5B5B;
            text-decoration: none; font-weight: 600; transition: all 0.3s ease; cursor: pointer;
            border: none; background: transparent; width: 100%;
        }
        .logout-link:hover { opacity: 0.8; transform: translateX(5px); color: #FF5B5B; }

        .main-content { margin-left: 280px; flex-grow: 1; padding: 40px; }
        .welcome-card { background: #ffffff; border-radius: 20px; padding: 18px 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01); border: none; }

        .table-card-wrapper { background: #ffffff; border-radius: 24px; padding: 35px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.01); border: none; position: relative; }
        .table-card-wrapper::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #4318FF 0%, #FFB800 100%); border-radius: 24px 24px 0 0; }

        .badge-bekerja { background-color: #EEF2FF; color: #4318FF; font-weight: 700; font-size: 0.8rem; padding: 6px 14px; border-radius: 8px; }
        .badge-studi { background-color: #FFF9E6; color: #FFB800; font-weight: 700; font-size: 0.8rem; padding: 6px 14px; border-radius: 8px; }
        .badge-mencari { background-color: #FFF2ED; color: #FF7043; font-weight: 700; font-size: 0.8rem; padding: 6px 14px; border-radius: 8px; }

        .btn-print-custom { background-color: #05CD99; color: white; border-radius: 12px; font-weight: 700; padding: 10px 22px; border: none; transition: 0.3s; }
        .btn-print-custom:hover { background-color: #04A87D; transform: scale(1.03); color: white; }

        @media (max-width: 992px) {
            body { flex-direction: column; }
            .sidebar-wrapper { position: static; width: 100%; height: auto; padding: 20px; }
            .sidebar-brand { margin-bottom: 20px; }
            .sidebar-menu { flex-direction: row; flex-wrap: wrap; gap: 10px; }
            .nav-link-custom { padding: 10px 15px; }
            .main-content { margin-left: 0; padding: 20px; }
        }
        @media (max-width: 576px) {
            .sidebar-menu { flex-direction: column; width: 100%; }
            .welcome-card { flex-direction: column !important; align-items: flex-start !important; gap: 10px; }
            .text-end { text-align: left !important; }
            .table-card-wrapper { padding: 20px 15px; }
            .btn-print-custom { width: 100%; margin-top: 10px; }
            .anim-title { flex-direction: column; align-items: flex-start !important; gap: 15px; }
        }

        @media print {
            .sidebar-wrapper, .welcome-card, .btn-print-custom, .sidebar-menu, .logout-link { display: none !important; }
            body { background-color: #ffffff; color: #000000; display: block; }
            .main-content { margin-left: 0; padding: 0; }
            .table-card-wrapper { box-shadow: none; padding: 0; border: none; }
            .table-card-wrapper::before { display: none; }
            th, td { border-bottom: 1px solid #000000 !important; }
        }
    </style>
</head>
<body>

    <div class="sidebar-wrapper anim-sidebar">
        <div>
            <div class="sidebar-brand">
                <i class="bi bi-mortarboard-fill"></i>
                <div>
                    <div class="sidebar-brand-text">SATUAN ALUMNI</div>
                    <div class="sidebar-brand-sub">UNIVERSITAS SATUAN</div>
                </div>
            </div>
            <div class="sidebar-menu">
                <a href="dashboard.php" class="nav-link-custom"><i class="bi bi-grid-fill"></i> Dashboard</a>
                <?php if ($user_role === 'admin') { ?>
                    <a href="tambah.php" class="nav-link-custom"><i class="bi bi-person-plus-fill"></i> Tambah Alumni</a>
                <?php } ?>
                <a href="laporan.php" class="nav-link-custom active"><i class="bi bi-printer-fill"></i> Cetak Laporan</a>
            </div>
        </div>
        <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" class="logout-link">
            <i class="bi bi-box-arrow-left"></i> Keluar Sistem
        </a>
    </div>

    <div class="main-content">
        <div class="card welcome-card d-flex flex-row justify-content-between align-items-center mb-4 anim-header">
            <h3 class="fw-bold mb-0 text-secondary text-uppercase" style="font-size: 1rem; letter-spacing: 0.5px;">Halaman Laporan</h3>
            <div class="text-end">
                <div class="fw-bold text-dark" style="font-size: 0.95rem;"><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></div>
                <div class="text-muted text-capitalize" style="font-size: 0.75rem; font-weight: 600;"><?= htmlspecialchars($user_role === 'admin' ? 'Master Administrator' : 'User'); ?></div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 anim-title">
            <div>
                <h3 class="fw-bold text-dark mb-1" style="font-size: 1.3rem;">Laporan Data Alumni</h3>
                <p class="text-muted small mb-0">Dokumen rekapitulasi data mahasiswa Universitas Satuan</p>
            </div>
            <button onclick="window.print()" class="btn btn-print-custom shadow-sm"><i class="bi bi-printer-fill me-1"></i> Cetak Dokumen</button>
        </div>

        <div class="card table-card-wrapper anim-table">
            <div class="table-responsive">
                <table class="table table-borderless text-center align-middle mb-0">
                    <thead>
                        <tr class="text-muted text-uppercase small border-bottom" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <th width="15%" class="pb-3">NIM</th>
                            <th width="30%" class="text-start pb-3">Nama Lengkap</th>
                            <th width="25%" class="pb-3">Email</th>
                            <th width="15%" class="pb-3">No Telepon</th>
                            <th width="15%" class="pb-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)) { ?>
                            <tr><td colspan="5" class="py-5 text-muted fw-medium">Belum ada data alumni tersimpan di database.</td></tr>
                        <?php } else { 
                            foreach ($data as $row) {
                                $status_str = strtolower($row['status_alumni'] ?? '');
                                $badge_class = (strpos($status_str, 'bekerja') !== false) ? 'badge-bekerja' : ((strpos($status_str, 'studi') !== false || strpos($status_str, 'lanjut') !== false) ? 'badge-studi' : 'badge-mencari');
                                ?>
                                <tr class="border-bottom" style="font-size: 0.88rem;">
                                    <td class="text-secondary font-monospace"><?= htmlspecialchars($row['nim'] ?? ''); ?></td>
                                    <td class="text-start fw-bold text-dark"><?= htmlspecialchars($row['nama_lengkap'] ?? ''); ?></td>
                                    <td class="text-secondary"><?= htmlspecialchars($row['email'] ?? ''); ?></td>
                                    <td class="text-secondary"><?= htmlspecialchars($row['no_telepon'] ?? ''); ?></td>
                                    <td><span class="<?= $badge_class; ?>"><?= htmlspecialchars($row['status_alumni'] ?? ''); ?></span></td>
                                </tr>
                            <?php } 
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                <div class="modal-body text-center p-5">
                    <div class="mb-4 d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: #FFF2ED; color: #FF5B5B; border-radius: 50%; font-size: 1.8rem;">
                        <i class="bi bi-box-arrow-left"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: #2B3674; letter-spacing: -0.5px;">Yakin ingin keluar?</h4>
                    <p class="text-muted small mb-4 px-3">Sesi aktif Anda akan segera diakhiri. Anda harus memasukkan kembali kredensial akun untuk mengelola data sistem alumni.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button type="button" class="btn fw-semibold px-4 py-2" data-bs-dismiss="modal" style="background-color: #F4F7FE; color: #A3AED0; border-radius: 12px; border: none;">Batal</button>
                        <a href="../controllers/AuthController.php?action=logout" class="btn fw-bold px-4 py-2 text-white" style="background-color: #FF5B5B; border-radius: 12px; border: none; box-shadow: 0 8px 20px rgba(255, 91, 91, 0.25); text-decoration: none;">Ya, Keluar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>