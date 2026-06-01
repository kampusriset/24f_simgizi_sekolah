<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM Gizi</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f2f8; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 2rem; }

        .login-container 
        { display: flex; 
        background: #fff;
        border-radius: 24px;
        border: 0.5px solid #e0e0f0; 
        overflow: hidden;
        width: 860px; min-height: 480px; }

        .login-right {
            flex:1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 40px }

        h2 { text-align: center; 
        margin-bottom: 4px; color: #1a1a2e;
        font-size: 22px; font-weight:800; }
        .login-subtitle {
            text-align: center;
            font-size: 13px;
            color: #8b8bab;
            margin-bottom: 28px;
            margin-top: 4px;
        
        }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #3a3a5c55; }

        input[type="text"], input[type="password"] { width: 100%; padding: 10px 14px; border: 1.5px solid #e4e4f0; border-radius: 10px; font-size: 14px; background: #fafafa; font-family: inherit; transition: border-color 0.2s, box shadow .2s; }
        input[type="text"]:focus, input[type="password"]:focus { border-color: #4f46e5; outline: none; border-radius: 10 px; font-size: 15px; cursor: pointer; font-weight: 600; font-family: inherit; box-shadow: 0 0 0 3px rgba(77,70,229,0.12); }

        .btn-login { width: 100%; padding: 12px; background: #4f46e5; color: #fff; border: none; border-radius: 10px; font-size: 16px; cursor: pointer; font-weight: 700; box-shadow: 0 0 0 3px rgba(77,70,229,0.12); }
        .btn-login:hover { background: #3730c8; tranform: translateY(-1px); }
        .error {
            background:  #ffebee; color: #c62828; padding: 10px 14px; border-radius: 10px; margin-bottom: 15px; border-left: 2px solid #e53935;
        }
       
        .register-link { text-align: center; margin-top: 20px; font-size: 14px; color: #8b8bab; }
        .register-link a { color: #4f46e5; text-decoration: none; font-weight: 600; }
        .register-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
    <div class="login-right">
        <h2>Login - SIM Gizi</h2>
        <p class = "login-subtitle" > Masuk Ke Akun Anda!</p>

        <!-- Menampilkan error jika variabel $error terisi -->
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <!-- Menambahkan value agar username tidak hilang jika password salah -->
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="register.php">Register di sini</a>
        </div>
    </div>
</body>
</html>