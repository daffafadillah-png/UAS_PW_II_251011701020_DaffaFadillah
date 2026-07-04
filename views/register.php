<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru | Satuan Alumni</title>
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
            margin: 0;
            padding: 20px;
        }
        @keyframes registerPopIn {
            0% { opacity: 0; transform: scale(0.96) translateY(25px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .register-card { 
            background: white; 
            border-radius: 24px; 
            box-shadow: 0 30px 60px rgba(67, 24, 255, 0.04); 
            border: none; 
            width: 100%; 
            max-width: 460px; 
            padding: 45px; 
            position: relative;
            animation: registerPopIn 0.55s cubic-bezier(0.175, 0.885, 0.32, 1.15) both;
        }
        .register-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px;
            background: linear-gradient(90deg, #4318FF 0%, #FFB800 100%); border-radius: 24px 24px 0 0;
        }
        .icon-user-box {
            width: 60px; height: 60px;
            background-color: #FFF9E6;
            color: #FFB800;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; margin: 0 auto 20px;
            transition: all 0.3s ease;
        }
        .register-card:hover .icon-user-box { transform: scale(1.08) rotate(8deg); }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #2B3674; margin-bottom: 6px; }
        .form-control, .form-select { 
            border-radius: 12px; padding: 12px 16px; border: 1px solid #E9EDF7; 
            background: #F8F9FA; color: #2B3674; font-size: 0.9rem; transition: all 0.25s ease; 
        }
        .form-control:focus, .form-select:focus { 
            background: #ffffff; border-color: #4318FF; 
            box-shadow: 0 8px 20px rgba(67, 24, 255, 0.06); transform: translateY(-1.5px); 
        }
        .btn-register-custom { 
            background: #4318FF; color: white; border-radius: 12px; padding: 12px; 
            font-weight: 700; border: none; transition: all 0.3s ease; letter-spacing: 0.5px;
        }
        .btn-register-custom:hover { 
            background: #3311CC; transform: translateY(-2px); 
            box-shadow: 0 10px 25px rgba(67, 24, 255, 0.23); color: white; 
        }
        .custom-alert {
            border-radius: 12px; font-size: 0.82rem; font-weight: 600;
            padding: 12px 16px; border: none; display: flex; align-items: center; gap: 10px;
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="card register-card">
        <div class="icon-user-box">
            <i class="bi bi-person-plus-fill"></i>
        </div>

        <h3 class="fw-bold text-center mb-1" style="color: #2B3674; letter-spacing: -0.5px;">Sign Up</h3>
        <p class="text-muted text-center small mb-4">Buat akun baru untuk mengakses sistem alumni</p>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'failed_register') { ?>
            <div class="alert alert-danger custom-alert mb-4 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>Username sudah terdaftar! Gunakan nama lain.</div>
            </div>
        <?php } ?>

        <form action="../controllers/AuthController.php?action=register" method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap Anda" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Buat username baru" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Buat password unik" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Hak Akses / Role</label>
                <select name="role" class="form-select" required>
                    <option value="user" selected>User / Anggota Biasa</option>
                    <option value="admin">Master Administrator</option>
                </select>
            </div>

            <button type="submit" name="register" class="btn btn-register-custom w-100 mb-4">Daftar Sekarang</button>
            
            <p class="small text-center text-muted mb-0">
                Sudah punya akun? <a href="login.php" class="text-decoration-none fw-bold" style="color: #4318FF;">Masuk Disini</a>
            </p>
        </form>
    </div>

</body>
</html>