<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../models/DataModel.php';
$data = (new DataModel())->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan | Satuan Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #EAF2FD 0%, #F3F7FD 100%); 
            color: #0F172A; 
            overflow-x: hidden;
            min-height: 100vh;
        }

        .report-workspace {
            max-width: 1140px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .card-table-wrapper {
            background: #FFFFFF; 
            border-radius: 20px; 
            border: none;
            box-shadow: 0 20px 35px -10px rgba(29, 78, 216, 0.05);
            padding: 40px; 
            position: relative; 
            overflow: hidden;
        }
        
        .card-table-wrapper::before {
            content: ''; 
            position: absolute; 
            top: 0; left: 0; right: 0; 
            height: 6px;
            background: linear-gradient(90deg, #1D4ED8 0%, #EAB308 100%);
        }
        
        .report-print-header {
            text-align: center; 
            margin-bottom: 35px; 
            border-bottom: 2px dashed #E2E8F0; 
            padding-bottom: 25px;
        }

        .table-custom thead th {
            background-color: #F8FAFC; 
            color: #64748B; 
            font-weight: 600;
            text-transform: uppercase; 
            font-size: 0.75rem; 
            border: 1px solid #E2E8F0; 
            padding: 14px;
        }
        .table-custom tbody td { 
            padding: 14px; 
            vertical-align: middle; 
            border: 1px solid #E2E8F0; 
            color: #334155; 
            font-size: 0.9rem; 
        }
        
        .btn-yellow-custom { 
            background: #EAB308; 
            color: #0F172A; 
            border-radius: 12px; 
            font-weight: 600; 
            padding: 12px 24px; 
            border: none; 
            transition: 0.2s; 
        }
        .btn-yellow-custom:hover { 
            background: #CA8A04; 
            color: #0F172A; 
            transform: translateY(-1px); 
            box-shadow: 0 4px 12px rgba(234, 179, 8, 0.2);
        }
        
        .btn-blue-custom { 
            background: #1D4ED8; 
            color: white; 
            border-radius: 12px; 
            font-weight: 600; 
            padding: 12px 24px; 
            border: none; 
            transition: 0.2s; 
        }
        .btn-blue-custom:hover { 
            background: #1E40AF; 
            color: white; 
            transform: translateY(-1px); 
        }

        .status-text { font-weight: 600; font-size: 0.85rem; }

     
        @media print {
            body { background: #FFFFFF !important; color: #000000 !important; padding: 0 !important; }
            .action-buttons-bar { display: none !important; } 
            .report-workspace { padding: 0 !important; max-width: 100% !important; margin: 0 !important; }
            .card-table-wrapper { box-shadow: none !important; padding: 0 !important; border: none !important; }
            .card-table-wrapper::before { display: none !important; }
            .table-custom { width: 100% !important; }
            .table-custom thead th { background-color: #F1F5F9 !important; color: #000000 !important; border: 1px solid #000000 !important; }
            .table-custom tbody td { border: 1px solid #000000 !important; color: #000000 !important; }
        }
    </style>
</head>
<body>


    <div class="report-workspace">

        <div class="d-flex justify-content-between align-items-center mb-4 action-buttons-bar">
            <div>
                <h4 class="fw-bold mb-1" style="letter-spacing: -0.5px; color: #0F1E36;">Dokumen Laporan Alumni</h4>
                <p class="text-muted small mb-0">Klik cetak sekarang untuk memproses data langsung ke printer atau berkas PDF.</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-yellow-custom shadow-sm"><i class="bi bi-printer-fill me-1"></i> Cetak Sekarang</button>
                <a href="dashboard.php" class="btn btn-blue-custom shadow-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>
        </div>

        <div class="card card-table-wrapper">
            
            <div class="report-print-header">
                <h3 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">LAPORAN DATA SATUAN ALUMNI</h3>
                <p class="text-muted small mb-0">Dicetak secara elektronik melalui sistem aplikasi pada tanggal: <span class="fw-semibold text-dark"><?= date('d F Y') ?></span></p>
            </div>

            <div class="table-responsive">
                <table class="table table-custom text-center align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="6%">No</th>
                            <th width="18%">NIM</th>
                            <th class="text-start" width="28%">Nama Lengkap</th>
                            <th width="23%">Email</th>
                            <th width="15%">No Telepon</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data)): ?>
                        <tr><td colspan="6" class="py-5 text-muted fw-medium">Tidak ada rekaman data alumni yang tersimpan di sistem.</td></tr>
                        <?php else: $no=1; foreach($data as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-semibold text-secondary"><?= htmlspecialchars($row['nim']) ?></td>
                            <td class="text-start fw-bold text-dark"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td class="text-secondary"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="text-secondary"><?= htmlspecialchars($row['no_telepon']) ?></td>
                            <td>
                                <span class="status-text fw-bold text-dark"><?= htmlspecialchars($row['status_alumni']) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>