<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../models/DataModel.php';
$data = (new DataModel())->getAll();

$total_alumni = count($data);
$bekerja = 0;
$lanjut_studi = 0;
$mencari_kerja = 0;
foreach($data as $row) {
    if(trim($row['status_alumni']) == 'Bekerja') $bekerja++;
    elseif(trim($row['status_alumni']) == 'Lanjut Studi') $lanjut_studi++;
    else $mencari_kerja++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sistem Informasi Satuan Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #EAF2FD 0%, #F3F7FD 100%); 
            color: #0F172A; 
            overflow-x: hidden;
        }
        .wrapper { display: flex; width: 100%; align-items: stretch; min-height: 100vh; }
        
        #sidebar {
            min-width: 270px;
            max-width: 270px;
            background: #0F1E36; 
            color: #E2E8F0;
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
            position: sticky;
            top: 0;
            height: 100vh;
            box-shadow: 4px 0 15px rgba(15, 30, 54, 0.08);
        }
        .sidebar-top { flex-grow: 1; }
        #sidebar .sidebar-header { padding: 30px 25px; border-bottom: 1px solid #1E293B; }
        
        #sidebar .sidebar-header span.subtitle-bright { 
            color: #CBD5E1 !important; 
            font-size: 0.75rem; 
            font-weight: 600;
            letter-spacing: 0.8px;
        }
        
        #sidebar ul.components { padding: 25px 15px; }
        #sidebar ul li a {
            padding: 14px 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #94A3B8;
            text-decoration: none;
            font-weight: 500;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: all 0.2s ease;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #FFFFFF;
            background: #1E293B;
        }
        #sidebar ul li.active > a {
            background: #1D4ED8; 
            color: white;
        }
        
        .sidebar-footer { padding: 20px 15px; border-top: 1px solid #1E293B; margin-bottom: 10px; }
        .logout-link {
            padding: 14px 20px; font-size: 0.95rem; display: flex; align-items: center; gap: 12px;
            color: #FDA4AF; text-decoration: none; font-weight: 600; border-radius: 10px; transition: all 0.2s ease;
        }
        .logout-link:hover { background: #881337; color: #FFFFFF; }
        
        #content { width: 100%; padding: 30px 40px; min-height: 100vh; }
        
        .top-navbar {
            background: #FFFFFF;
            padding: 18px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(29, 78, 216, 0.03);
            margin-bottom: 30px;
        }

        .card-stat {
            background: #FFFFFF; border: none; border-radius: 16px; padding: 20px;
            box-shadow: 0 10px 25px -5px rgba(29, 78, 216, 0.03);
            display: flex; align-items: center; gap: 15px;
            transition: transform 0.2s ease;
        }
        .card-stat:hover { transform: translateY(-3px); }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
        }
        
        .bg-stat-total { background-color: #EEF2FF; color: #4F46E5; }
        .bg-stat-bekerja { background-color: #ECFDF5; color: #059669; }
        .bg-stat-studi { background-color: #EFF6FF; color: #2563EB; }
        .bg-stat-mencari { background-color: #FFF7ED; color: #EA580C; }
        
        .card-table-wrapper {
            background: #FFFFFF; 
            border-radius: 20px; 
            border: none;
            box-shadow: 0 20px 35px -10px rgba(29, 78, 216, 0.05);
            padding: 25px;
            position: relative;
            overflow: hidden;
        }
        
        .card-table-wrapper::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, #1D4ED8 0%, #EAB308 100%);
        }
        
        .table-custom thead th {
            background-color: #F8FAFC; color: #64748B; font-weight: 600;
            text-transform: uppercase; font-size: 0.75rem; border-bottom: 1px solid #E2E8F0; padding: 16px;
        }
        .table-custom tbody td { padding: 16px; vertical-align: middle; border-bottom: 1px solid #F1F5F9; color: #334155; font-size: 0.9rem; }
        
      
        .badge-custom { padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.75rem; display: inline-block; }
        .bg-soft-blue-status { background-color: #EEF2FF; color: #3730A3; border: 1px solid #C7D2FE; }
        .bg-soft-yellow-status { background-color: #FEF9C3; color: #713F12; border: 1px solid #FEF08A; }
        .bg-soft-orange-status { background-color: #FFEDD5; color: #9A3412; border: 1px solid #FED7AA; }
        
      
        .btn-yellow-custom { background: #EAB308; color: #0F172A; border-radius: 10px; font-weight: 600; padding: 10px 20px; border: none; transition: 0.2s; }
        .btn-yellow-custom:hover { background: #CA8A04; color: #0F172A; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(234, 179, 8, 0.2); }

        .btn-action { border-radius: 8px; font-weight: 600; font-size: 0.85rem; padding: 6px 10px; border: none; transition: 0.2s; background-color: #F8FAFC; }
        .btn-action:hover { background-color: #E2E8F0; }

        .avatar-circle { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .avatar-placeholder {
            width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; 
            justify-content: center; font-weight: 700; margin: 0 auto; box-shadow: inset 0 0 0 1px rgba(0,0,0,0.03);
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-top">
                <div class="sidebar-header d-flex align-items-center gap-2">
                    <i class="bi bi-mortarboard-fill text-warning fs-3"></i>
                    <div>
                        <h5 class="fw-bold mb-0 text-white" style="letter-spacing: -0.3px;">SATUAN ALUMNI</h5>
                        <span class="subtitle-bright text-uppercase">Universitas Satuan</span>
                    </div>
                </div>

                <ul class="list-unstyled components">
                    <li class="active">
                        <a href="dashboard.php"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="tambah.php"><i class="bi bi-person-plus-fill"></i> Tambah Alumni</a>
                    </li>
                    <li>
                        <a href="laporan.php"><i class="bi bi-printer-fill"></i> Cetak Laporan</a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <a href="../controllers/AuthController.php?action=logout" class="logout-link">
                    <i class="bi bi-box-arrow-left"></i> Keluar Sistem
                </a>
            </div>
        </nav>


        <div id="content">
            
            <div class="top-navbar d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-secondary" style="font-size: 1.20rem;">SELAMAT DATANG</h5>
                </div>
                <div class="text-end">
                    <p class="small fw-bold mb-0 text-dark"><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Administrator') ?></p>
                    <span class="text-muted" style="font-size: 0.75rem; font-weight: 500;">Admin</span>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="card-stat">
                        <div class="stat-icon bg-stat-total"><i class="bi bi-people-fill"></i></div>
                        <div><span class="text-muted small fw-medium d-block">Total Alumni</span><h5 class="fw-bold mb-0"><?= $total_alumni ?></h5></div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card-stat">
                        <div class="stat-icon bg-stat-bekerja"><i class="bi bi-briefcase-fill"></i></div>
                        <div><span class="text-muted small fw-medium d-block">Bekerja</span><h5 class="fw-bold mb-0"><?= $bekerja ?></h5></div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card-stat">
                        <div class="stat-icon bg-stat-studi"><i class="bi bi-book-fill"></i></div>
                        <div><span class="text-muted small fw-medium d-block">Lanjut Studi</span><h5 class="fw-bold mb-0"><?= $lanjut_studi ?></h5></div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card-stat">
                        <div class="stat-icon bg-stat-mencari"><i class="bi bi-hourglass-split"></i></div>
                        <div><span class="text-muted small fw-medium d-block">Mencari Kerja</span><h5 class="fw-bold mb-0"><?= $mencari_kerja ?></h5></div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                <div>
                    <h4 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Data Alumni</h4>
                    <p class="text-muted small mb-0">Ini adalah kumpulan data Alumni mahasiswa Universitas Satuan</p>
                </div>
                <a href="tambah.php" class="btn btn-yellow-custom shadow-sm"><i class="bi bi-plus-lg me-1"></i> Tambah Data</a>
            </div>

            <div class="card card-table-wrapper">
                <div class="table-responsive">
                    <table class="table table-custom table-borderless text-center mb-0">
                        <thead>
                            <tr>
                                <th width="8%">Foto</th>
                                <th width="15%">NIM</th>
                                <th class="text-start" width="22%">Nama Lengkap</th>
                                <th width="20%">Email</th>
                                <th width="15%">No Telepon</th>
                                <th width="12%">Status</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data)): ?>
                            <tr><td colspan="7" class="py-5 text-muted fw-medium">Tidak ada data records di dalam database.</td></tr>
                            <?php else: foreach($data as $row): ?>
                            <tr>
                                <td>
                                    <?php 
                                    $foto_path = "../uploads/" . $row['foto'];
                                    if (!empty($row['foto']) && file_exists($foto_path) && is_file($foto_path)): 
                                    ?>
                                        <img src="<?= $foto_path ?>" class="avatar-circle" alt="User">
                                    <?php else: 
                                        $char = strtoupper(substr($row['nama_lengkap'], 0, 1));
                                        if (in_array($char, ['A','B','C','D','E'])) {
                                            $av_bg = "background-color: #EEF2FF; color: #4F46E5;";
                                        } elseif (in_array($char, ['F','G','H','I','J'])) {
                                            $av_bg = "background-color: #ECFDF5; color: #059669;";
                                        } elseif (in_array($char, ['K','L','M','N','O'])) {
                                            $av_bg = "background-color: #EFF6FF; color: #2563EB;";
                                        } elseif (in_array($char, ['P','Q','R','S','T'])) {
                                            $av_bg = "background-color: #FFF7ED; color: #EA580C;";
                                        } else {
                                            $av_bg = "background-color: #FFF1F2; color: #E11D48;";
                                        }
                                    ?>
                                        <div class="avatar-placeholder" style="<?= $av_bg ?>">
                                            <?= $char ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-secondary fw-semibold"><?= htmlspecialchars($row['nim']) ?></td>
                                <td class="text-start fw-bold text-dark">
                                    <a href="edit.php?nim=<?= $row['nim'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($row['nama_lengkap']) ?></a>
                                </td>
                                <td class="text-secondary"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="text-secondary"><?= htmlspecialchars($row['no_telepon']) ?></td>
                                <td>
                                    <span class="badge-custom <?= $row['status_alumni'] == 'Bekerja' ? 'bg-soft-blue-status' : ($row['status_alumni'] == 'Lanjut Studi' ? 'bg-soft-yellow-status' : 'bg-soft-orange-status') ?>">
                                        <?= htmlspecialchars($row['status_alumni']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="edit.php?nim=<?= $row['nim'] ?>" class="btn-action text-primary" title="Ubah Data"><i class="bi bi-pencil-square"></i></a>
                                        <a href="../controllers/DataController.php?action=delete&nim=<?= $row['nim'] ?>" class="btn-action text-danger" onclick="return confirm('Hapus data alumni ini?')" title="Hapus Data"><i class="bi bi-trash-fill"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
</html>