<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../models/DataModel.php';
$data = (new DataModel())->getAll();

$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';

$total_alumni  = count($data);
$count_bekerja = 0;
$count_studi   = 0;
$count_mencari = 0;

foreach ($data as $row) {
    $status = strtolower($row['status_alumni'] ?? '');
    if (strpos($status, 'bekerja') !== false) {
        $count_bekerja++;
    } elseif (strpos($status, 'studi') !== false || strpos($status, 'lanjut') !== false) {
        $count_studi++;
    } else {
        $count_mencari++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Satuan Alumni</title>
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
        .anim-metrics { animation: fadeInUp 0.6s cubic-bezier(0.25, 1, 0.5, 1) both; animation-delay: 0.2s; }
        .anim-title { animation: fadeInUp 0.6s cubic-bezier(0.25, 1, 0.5, 1) both; animation-delay: 0.3s; }
        .anim-table { animation: fadeInUp 0.6s cubic-bezier(0.25, 1, 0.5, 1) both; animation-delay: 0.4s; }

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

        .metric-card {
            background: #ffffff; border-radius: 16px; padding: 14px 20px; border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01); display: flex; align-items: center; gap: 14px;
            transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        }
        .metric-card:hover { transform: translateY(-6px); box-shadow: 0 15px 30px rgba(67, 24, 255, 0.08); }
        .metric-icon-box { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; }
        
        .icon-total { background: #EEF2FF; color: #4318FF; }
        .icon-bekerja { background: #E6F9F0; color: #05CD99; }
        .icon-studi { background: #FFF9E6; color: #FFB800; }
        .icon-mencari { background: #FFF2ED; color: #FF7043; }

        .table-card-wrapper { background: #ffffff; border-radius: 24px; padding: 35px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.01); border: none; position: relative; }
        .table-card-wrapper::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #4318FF 0%, #FFB800 100%); border-radius: 24px 24px 0 0; }

        .avatar-badge { width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; }
        .alumni-photo { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid #F4F7FE; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        
        .badge-bekerja { background-color: #EEF2FF; color: #4318FF; font-weight: 700; font-size: 0.8rem; padding: 6px 14px; border-radius: 8px; }
        .badge-studi { background-color: #FFF9E6; color: #FFB800; font-weight: 700; font-size: 0.8rem; padding: 6px 14px; border-radius: 8px; }
        .badge-mencari { background-color: #FFF2ED; color: #FF7043; font-weight: 700; font-size: 0.8rem; padding: 6px 14px; border-radius: 8px; }

        .btn-add-custom { background-color: #FFB800; color: #0B1931; border-radius: 12px; font-weight: 700; padding: 10px 22px; border: none; transition: 0.3s; }
        .btn-add-custom:hover { background-color: #E2A300; transform: scale(1.03); }
        
        .action-btn { font-size: 1rem; padding: 4px 8px; border-radius: 6px; text-decoration: none; display: inline-flex; transition: 0.2s; }
        .btn-edit-outline { color: #4318FF; border: 1px solid #EEF2FF; background: #fff; }
        .btn-edit-outline:hover { background: #EEF2FF; transform: scale(1.1); }
        .btn-delete-outline { color: #FF5B5B; border: 1px solid #FFF2ED; background: #fff; }
        .btn-delete-outline:hover { background: #FFF2ED; transform: scale(1.1); }

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
                <a href="dashboard.php" class="nav-link-custom active"><i class="bi bi-grid-fill"></i> Dashboard</a>
                <?php if ($user_role === 'admin') { ?>
                    <a href="tambah.php" class="nav-link-custom"><i class="bi bi-person-plus-fill"></i> Tambah Alumni</a>
                <?php } ?>
                <a href="laporan.php" class="nav-link-custom"><i class="bi bi-printer-fill"></i> Cetak Laporan</a>
            </div>
        </div>
        <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" class="logout-link">
            <i class="bi bi-box-arrow-left"></i> Keluar Sistem
        </a>
    </div>

    <div class="main-content">
        <div class="card welcome-card d-flex flex-row justify-content-between align-items-center mb-4 anim-header">
            <h3 class="fw-bold mb-0 text-secondary text-uppercase" style="font-size: 1rem; letter-spacing: 0.5px;">Selamat Datang</h3>
            <div class="text-end">
                <div class="fw-bold text-dark" style="font-size: 0.95rem;"><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></div>
                <div class="text-muted text-capitalize" style="font-size: 0.75rem; font-weight: 600;"><?= htmlspecialchars($user_role === 'admin' ? 'Master Administrator' : 'User'); ?></div>
            </div>
        </div>

        <?php if (isset($_GET['status'])) { ?>
            <?php if ($_GET['status'] === 'success_delete') { ?>
                <div id="toast-alert" class="alert alert-success border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert" style="font-size: 0.88rem; font-weight: 600; animation: fadeInUp 0.4s ease; background-color: #E6F9F0; color: #05CD99; transition: all 0.5s ease;">
                    <i class="bi bi-trash-fill fs-5"></i>
                    <div>Data alumni telah berhasil dihapus secara permanen dari sistem.</div>
                </div>
            <?php } elseif ($_GET['status'] === 'success_add') { ?>
                <div id="toast-alert" class="alert alert-success border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert" style="font-size: 0.88rem; font-weight: 600; animation: fadeInUp 0.4s ease; background-color: #E6F9F0; color: #05CD99; transition: all 0.5s ease;">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>Data alumni baru telah sukses ditambahkan ke dalam database!</div>
                </div>
            <?php } elseif ($_GET['status'] === 'success_update') { ?>
                <div id="toast-alert" class="alert alert-success border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert" style="font-size: 0.88rem; font-weight: 600; animation: fadeInUp 0.4s ease; background-color: #E6F9F0; color: #05CD99; transition: all 0.5s ease;">
                    <i class="bi bi-arrow-clockwise fs-5"></i>
                    <div>Perubahan data informasi alumni berhasil diperbarui sepenuhnya!</div>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="row g-3 mb-4 anim-metrics">
            <div class="col-md-3 col-6">
                <div class="card metric-card">
                    <div class="metric-icon-box icon-total"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <div class="text-muted small fw-medium" style="font-size: 0.8rem;">Total Alumni</div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;"><?= $total_alumni; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card metric-card">
                    <div class="metric-icon-box icon-bekerja"><i class="bi bi-briefcase-fill"></i></div>
                    <div>
                        <div class="text-muted small fw-medium" style="font-size: 0.8rem;">Bekerja</div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;"><?= $count_bekerja; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card metric-card">
                    <div class="metric-icon-box icon-studi"><i class="bi bi-book-fill"></i></div>
                    <div>
                        <div class="text-muted small fw-medium" style="font-size: 0.8rem;">Lanjut Studi</div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;"><?= $count_studi; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card metric-card">
                    <div class="metric-icon-box icon-mencari"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <div class="text-muted small fw-medium" style="font-size: 0.8rem;">Mencari Kerja</div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;"><?= $count_mencari; ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-start mb-4 anim-title">
            <div>
                <h3 class="fw-bold text-dark mb-1" style="font-size: 1.3rem;">Data Alumni</h3>
                <p class="text-muted small mb-0">Kumpulan data Alumni Universitas Satuan</p>
            </div>
            <?php if ($user_role === 'admin') { ?>
                <a href="tambah.php" class="btn btn-add-custom px-4 shadow-sm"><i class="bi bi-plus-lg me-1"></i> Tambah</a>
            <?php } ?>
        </div>

        <div class="card table-card-wrapper anim-table">
            <div class="table-responsive">
                <table class="table table-borderless text-center align-middle mb-0">
                    <thead>
                        <tr class="text-muted text-uppercase small border-bottom" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <th width="8%" class="pb-3">Foto</th>
                            <th width="15%" class="pb-3">NIM</th>
                            <th width="25%" class="text-start pb-3">Nama Lengkap</th>
                            <th width="22%" class="pb-3">Email</th>
                            <th width="15%" class="pb-3">No Telepon</th>
                            <th width="15%" class="pb-3">Status</th>
                            <?php if ($user_role === 'admin') { ?>
                                <th width="10%" class="pb-3">Aksi</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)) { ?>
                            <tr><td colspan="<?= ($user_role === 'admin') ? '7' : '6'; ?>" class="py-5 text-muted fw-medium">Belum ada data alumni tersimpan di database.</td></tr>
                        <?php } else { 
                            $colors = ['#EEF2FF', '#E6F9F0', '#FFF9E6', '#FFF2ED'];
                            $text_colors = ['#4318FF', '#05CD99', '#FFB800', '#FF7043'];
                            
                            foreach ($data as $index => $row) {
                                $initial = strtoupper(substr($row['nama_lengkap'] ?? 'A', 0, 1));
                                $color_idx = $index % count($colors);
                                $status_str = strtolower($row['status_alumni'] ?? '');
                                $badge_class = (strpos($status_str, 'bekerja') !== false) ? 'badge-bekerja' : ((strpos($status_str, 'studi') !== false || strpos($status_str, 'lanjut') !== false) ? 'badge-studi' : 'badge-mencari');
                                $foto_path = "../uploads/" . ($row['foto'] ?? '');
                                ?>
                                <tr class="border-bottom" style="font-size: 0.88rem;">
                                    <td class="py-3">
                                        <?php if (!empty($row['foto']) && $row['foto'] !== 'default.png' && file_exists($foto_path)) { ?>
                                            <img src="<?= $foto_path; ?>" alt="Foto" class="alumni-photo">
                                        <?php } else { ?>
                                            <div class="avatar-badge" style="background-color: <?= $colors[$color_idx]; ?>; color: <?= $text_colors[$color_idx]; ?>;"><?= $initial; ?></div>
                                        <?php } ?>
                                    </td>
                                    <td class="text-secondary font-monospace"><?= htmlspecialchars($row['nim'] ?? ''); ?></td>
                                    <td class="text-start fw-bold text-dark"><?= htmlspecialchars($row['nama_lengkap'] ?? ''); ?></td>
                                    <td class="text-secondary"><?= htmlspecialchars($row['email'] ?? ''); ?></td>
                                    <td class="text-secondary"><?= htmlspecialchars($row['no_telepon'] ?? ''); ?></td>
                                    <td><span class="<?= $badge_class; ?>"><?= htmlspecialchars($row['status_alumni'] ?? ''); ?></span></td>
                                    <?php if ($user_role === 'admin') { ?>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="edit.php?nim=<?= $row['nim']; ?>" class="action-btn btn-edit-outline" title="Ubah Data"><i class="bi bi-pencil-square"></i></a>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" data-nim="<?= $row['nim']; ?>" class="action-btn btn-delete-outline" title="Hapus Data">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            </div>
                                        </td>
                                    <?php } ?>
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

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                <div class="modal-body text-center p-5">
                    <div class="mb-4 d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: #FFF2ED; color: #FF5B5B; border-radius: 50%; font-size: 1.8rem;">
                        <i class="bi bi-trash-fill"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: #2B3674; letter-spacing: -0.5px;">Hapus data alumni?</h4>
                    <p class="text-muted small mb-4 px-3">Tindakan ini tidak dapat dibatalkan. Data alumni yang dipilih akan dihapus secara permanen dari database.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button type="button" class="btn fw-semibold px-4 py-2" data-bs-dismiss="modal" style="background-color: #F4F7FE; color: #A3AED0; border-radius: 12px; border: none;">Batal</button>
                        <a id="confirmDeleteBtn" href="#" class="btn fw-bold px-4 py-2 text-white" style="background-color: #FF5B5B; border-radius: 12px; border: none; box-shadow: 0 8px 20px rgba(255, 91, 91, 0.25); text-decoration: none;">Ya, Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertElement = document.getElementById('toast-alert');
            if (alertElement) {
                setTimeout(function() {
                    alertElement.style.opacity = '0';
                    alertElement.style.transform = 'translateY(-15px)';
                    setTimeout(function() {
                        alertElement.remove();
                    }, 500);
                }, 4000);
            }

            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const nim = button.getAttribute('data-nim');
                    const confirmBtn = document.getElementById('confirmDeleteBtn');
                    confirmBtn.setAttribute('href', '../controllers/DataController.php?action=delete&nim=' + nim);
                });
            }
        });
    </script>
</body>
</html>