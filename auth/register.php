<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../config/database.php';
require '../models/User.php';

$db = (new Database())->connect();
$userModel = new User($db);
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validasi input kosong
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi!";
    } elseif (strlen($username) < 3) {
        $error = "Username minimal 3 karakter!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username sudah ada
        $existingUser = $userModel->login($username);
        if ($existingUser) {
            $error = "Username sudah digunakan, pilih yang lain!";
        } else {
            // Gunakan method register() yang benar
            if ($userModel->register($username, $password)) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Registrasi gagal, coba lagi!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIM Gizi</title>
    <style>
     { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f2f8; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 2rem; }

    .register-container { background: #fff; border-radius: 24px; border: 0.5px solid #e0e0f0; overflow: hidden; width: 860px; box-shadow: 0 8px 32px rgba(79,70,229,0.08); padding: 48px 40px; }

    h2 { text-align: center; margin-bottom: 6px; color: #1a1a2e; font-size: 22px; font-weight: 800; }
    .register-subtitle { text-align: center; font-size: 13px; color: #8b8bab; margin-bottom: 28px; margin-top: 4px; }
    .form-group { margin-bottom: 16px; }

    label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #9898b8; }
    input[type="text"], input[type="password"] { width: 100%; padding: 10px 14px; border: 1.5px solid #e4e4f0; border-radius: 10px; font-size: 14px; background: #fafafa; font-family: inherit; transition: border-color 0.2s, box-shadow 0.2s; }
    input[type="text"]:focus, input[type="password"]:focus { border-color: #4f46e5; outline: none; background: #fff; box-shadow: 0 0 0 3px rgba(79,70,229,0.12); }
    
    .btn-register { width: 100%; padding: 12px; background: #4f46e5; color: #fff; border: none; border-radius: 10px; font-size: 15px; cursor: pointer; font-weight: 700; font-family: inherit; box-shadow: 0 4px 14px rgba(79,70,229,0.35); transition: background 0.2s, transform 0.1s; }
    .btn-register:hover { background: #3730c8; transform: translateY(-1px); }
    .error { background: #ffebee; color: #c62828; padding: 10px 14px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; border-left: 3px solid #e53935; }
    .success { background: #e8f5e9; color: #2e7d32; padding: 10px 14px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; border-left: 3px solid #43a047; }
    
    .login-link { text-align: center; margin-top: 20px; font-size: 13px; color: #8b8bab; }
    .login-link a { color: #4f46e5; text-decoration: none; font-weight: 700; }
    .login-link a:hover { text-decoration: underline; }
</style>
</head>
<body>
    <div class="register-container">
        <h2>Register - SIM Gizi</h2>
        <p class = "register-subtitle" > Buat Akun Baru Kamu! </p>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required minlength="3" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
            </div>

            <button type="submit" class="btn-register">Register</button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>
</html>