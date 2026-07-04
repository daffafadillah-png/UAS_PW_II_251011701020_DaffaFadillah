<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Satuan Alumni</title>
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
        @keyframes loginPopIn {
            0% { opacity: 0; transform: scale(0.96) translateY(25px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes alertShake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
        .login-card { 
            background: white; 
            border-radius: 24px; 
            box-shadow: 0 30px 60px rgba(67, 24, 255, 0.04); 
            border: none; 
            width: 100%; 
            max-width: 420px; 
            padding: 45px; 
            position: relative;
            animation: loginPopIn 0.55s cubic-bezier(0.175, 0.885, 0.32, 1.15) both;
        }
        .login-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px;
            background: linear-gradient(90deg, #4318FF 0%, #FFB800 100%); border-radius: 24px 24px 0 0;
        }
        .icon-lock-box {
            width: 60px; height: 60px;
            background-color: #EEF2FF;
            color: #4318FF;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; margin: 0 auto 20px;
            transition: all 0.3s ease;
        }
        .login-card:hover .icon-lock-box { transform: scale(1.08) rotate(-8deg); }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #2B3674; margin-bottom: 6px; }
        .form-control { 
            border-radius: 12px; padding: 12px 16px; border: 1px solid #E9EDF7; 
            background: #F8F9FA; color: #2B3674; font-size: 0.9rem; transition: all 0.25s ease; 
        }
        .form-control:focus { 
            background: #ffffff; border-color: #4318FF; 
            box-shadow: 0 8px 20px rgba(67, 24, 255, 0.06); transform: translateY(-1.5px); 
        }
        .btn-login-custom { 
            background: #4318FF; color: white; border-radius: 12px; padding: 12px; 
            font-weight: 700; border: none; transition: all 0.3s ease; letter-spacing: 0.5px;
        }
        .btn-login-custom:hover { 
            background: #3311CC; transform: translateY(-2px); 
            box-shadow: 0 10px 25px rgba(67, 24, 255, 0.23); color: white; 
        }
        .custom-alert {
            border-radius: 12px; font-size: 0.82rem; font-weight: 600;
            padding: 12px 16px; border: none; display: flex; align-items: center; gap: 10px;
        }
        .alert-shake-action { animation: alertShake 0.4s ease-in-out; }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="icon-lock-box">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <h3 class="fw-bold text-center mb-1" style="color: #2B3674; letter-spacing: -0.5px;">Sign In</h3>
        <p class="text-muted text-center small mb-4">Masukkan kredensial untuk masuk ke sistem</p>

        <?php if (isset($_GET['status'])) { ?>
            <?php if ($_GET['status'] === 'failed_login') { ?>
                <div class="alert alert-danger custom-alert alert-shake-action mb-4 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>Username atau Password salah! Gagal masuk.</div>
                </div>
            <?php } elseif ($_GET['status'] === 'success_register') { ?>
                <div class="alert alert-success custom-alert mb-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>Akun berhasil dibuat! Silakan login.</div>
                </div>
            <?php } ?>
        <?php } ?>

        <form action="../controllers/AuthController.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border: 1px solid #E9EDF7;"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" class="form-control border-start-0" style="border-radius: 0 12px 12px 0;" placeholder="Masukkan username" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border: 1px solid #E9EDF7;"><i class="bi bi-key-fill"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" style="border-radius: 0 12px 12px 0;" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-login-custom w-100 mb-4">Sign In</button>
            
            <p class="small text-center text-muted mb-0">
                Belum terdaftar? <a href="register.php" class="text-decoration-none fw-bold" style="color: #4318FF;">Buat Akun Anggota</a>
            </p>
        </form>
    </div>

</body>
</html>