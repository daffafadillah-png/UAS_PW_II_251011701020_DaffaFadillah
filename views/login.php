<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F4F7FE; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #white; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.03); border: none; width: 100%; max-width: 400px; padding: 40px; }
        .form-control { border-radius: 12px; padding: 12px; border: 1px solid #E9EDF7; background: #F8F9FA; }
        .btn-custom { background: #4318FF; color: white; border-radius: 12px; padding: 12px; font-weight: 600; border: none; transition: 0.3s; }
        .btn-custom:hover { background: #3311DB; transform: translateY(-2px); color: white; }
    </style>
</head>
<body>
    <div class="card login-card bg-white">
        <h3 class="fw-bold text-center mb-1" style="color: #2B3674;">Welcome Back</h3>
        <p class="text-muted text-center small mb-4">Silakan masuk menggunakan akun Anda</p>
        <form action="../controllers/AuthController.php?action=login" method="POST">
            <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
            <div class="mb-4"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
            <button type="submit" class="btn btn-custom w-100 mb-3">Sign In</button>
            <p class="small text-center text-muted mb-0">Belum punya akun? <a href="register.php" class="text-decoration-none fw-semibold" style="color: #4318FF;">Daftar di sini</a></p>
        </form>
    </div>
</body>
</html>