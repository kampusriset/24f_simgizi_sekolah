<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Diagnosis Database SIM Gizi</h2>";

// ============================================
// 1. CEK KONEKSI DATABASE
// ============================================
echo "<h3>1. Cek Koneksi Database</h3>";
require 'config/database.php';

try {
    $db = (new Database())->connect();
    echo "<p style='color:green;font-weight:bold;'>✅ Koneksi database BERHASIL!</p>";
    echo "<p>Server info: " . $db->server_info . "</p>";
} catch (Exception $e) {
    echo "<p style='color:red;font-weight:bold;'>❌ Koneksi database GAGAL!</p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Pastikan:</p>";
    echo "<ul>";
    echo "<li>MySQL/MariaDB sudah berjalan</li>";
    echo "<li>Database 'sim_gizi' sudah dibuat</li>";
    echo "<li>Username/password di config/database.php sudah benar</li>";
    echo "</ul>";
    die();
}

// ============================================
// 2. CEK TABEL USERS ADA ATAU TIDAK
// ============================================
echo "<h3>2. Cek Tabel Users</h3>";
$result = $db->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows > 0) {
    echo "<p style='color:green;'>✅ Tabel 'users' ditemukan</p>";

    // Cek struktur tabel
    echo "<p>Struktur tabel users:</p>";
    $columns = $db->query("DESCRIBE users");
    echo "<table border='1' cellpadding='6' cellspacing='0'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($col = $columns->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Cek apakah kolom 'role' ada
    $hasRole = $db->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($hasRole->num_rows > 0) {
        echo "<p style='color:green;'>✅ Kolom 'role' ada</p>";
    } else {
        echo "<p style='color:red;'>❌ Kolom 'role' TIDAK ADA! Login akan error karena login.php mengakses \$user['role']</p>";
        echo "<p>Jalankan: <code>ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user';</code></p>";
    }
} else {
    echo "<p style='color:red;'>❌ Tabel 'users' TIDAK DITEMUKAN!</p>";
    echo "<p>Jalankan SQL di bawah untuk membuat tabel:</p>";
    echo "<pre>CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);</pre>";
    die();
}

// ============================================
// 3. CEK DATA USER ADMIN
// ============================================
echo "<h3>3. Cek Data User di Database</h3>";
$users = $db->query("SELECT * FROM users");
echo "<p>Total user: <b>" . $users->num_rows . "</b></p>";

if ($users->num_rows > 0) {
    echo "<table border='1' cellpadding='6' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password (hash)</th><th>Role</th></tr>";
    while ($u = $users->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $u['id'] . "</td>";
        echo "<td>" . htmlspecialchars($u['username']) . "</td>";
        echo "<td style='font-size:11px;max-width:300px;word-break:break-all;'>" . htmlspecialchars($u['password']) . "</td>";
        echo "<td>" . ($u['role'] ?? 'TIDAK ADA') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>❌ Tidak ada user di database! Buat admin default:</p>";
}

// ============================================
// 4. CEK ADMIN KHUSUS
// ============================================
echo "<h3>4. Cek User Admin</h3>";
$adminResult = $db->query("SELECT * FROM users WHERE username = 'admin'");
if ($adminResult->num_rows > 0) {
    $admin = $adminResult->fetch_assoc();
    echo "<p style='color:green;'>✅ User 'admin' ditemukan</p>";
    echo "<p>Username: <b>" . htmlspecialchars($admin['username']) . "</b></p>";
    echo "<p>Password hash: <code style='font-size:11px;'>" . htmlspecialchars($admin['password']) . "</code></p>";
    echo "<p>Role: <b>" . ($admin['role'] ?? 'TIDAK ADA') . "</b></p>";

    // Cek panjang hash
    $hashLen = strlen($admin['password']);
    echo "<p>Panjang hash: <b>$hashLen</b> karakter";
    if ($hashLen < 50) {
        echo " <span style='color:red;'>❌ TERLALU PENDEK! Hash bcrypt seharusnya 60 karakter. Password belum di-hash!</span>";
    } elseif ($hashLen === 60) {
        echo " <span style='color:green;'>✅ Panjang hash benar (bcrypt)</span>";
    } else {
        echo " <span style='color:orange;'>⚠️ Panjang tidak standar bcrypt (60 karakter)</span>";
    }
    echo "</p>";

    // ============================================
    // 5. TEST PASSWORD_VERIFY
    // ============================================
    echo "<h3>5. Test password_verify()</h3>";

    // Test beberapa password yang mungkin
    $testPasswords = ['admin123', 'admin', 'password', 'Admin123'];

    foreach ($testPasswords as $testPw) {
        $result = password_verify($testPw, $admin['password']);
        if ($result) {
            echo "<p style='color:green;font-weight:bold;'>✅ Password '<b>$testPw</b>' → COCOK!</p>";
        } else {
            echo "<p style='color:gray;'>❌ Password '$testPw' → tidak cocok</p>";
        }
    }

} else {
    echo "<p style='color:red;'>❌ User 'admin' TIDAK DITEMUKAN di database!</p>";
    echo "<p>Buat admin dengan menjalankan file <a href='fix_password.php'>fix_password.php</a></p>";
    echo "<p>Atau jalankan SQL:</p>";
    echo "<pre>INSERT INTO users (username, password, role) VALUES (
    'admin',
    '" . password_hash('admin123', PASSWORD_DEFAULT) . "',
    'admin'
);</pre>";
}

// ============================================
// 6. TEST LOGIN LANGSUNG
// ============================================
echo "<h3>6. Simulasi Login</h3>";
require 'models/User.php';
$userModel = new User($db);

$testLogin = $userModel->login('admin');
if ($testLogin) {
    echo "<p style='color:green;'>✅ User::login('admin') → data ditemukan</p>";
    echo "<pre>";
    print_r($testLogin);
    echo "</pre>";

    $testVerify = password_verify('admin123', $testLogin['password']);
    echo "<p>password_verify('admin123', hash) → ";
    if ($testVerify) {
        echo "<span style='color:green;font-weight:bold;'>TRUE ✅ LOGIN HARUSNYA BERHASIL!</span>";
    } else {
        echo "<span style='color:red;font-weight:bold;'>FALSE ❌ Password tidak cocok!</span>";
        echo "<p style='color:red;'>Solusi: Jalankan <a href='fix_password.php'>fix_password.php</a> untuk reset password admin</p>";
    }
    echo "</p>";
} else {
    echo "<p style='color:red;'>❌ User::login('admin') → NULL, user tidak ditemukan</p>";
}

echo "<hr>";
echo "<h3>Kesimpulan & Solusi</h3>";
echo "<p>Jika login masih gagal, kemungkinan:</p>";
echo "<ol>";
echo "<li><b>Password belum di-hash</b> — Jalankan <a href='fix_password.php'>fix_password.php</a></li>";
echo "<li><b>Kolom role belum ada</b> — Login.php mengakses \$user['role'], jika kolom tidak ada akan error</li>";
echo "<li><b>Password hash rusak</b> — Jalankan fix_password.php untuk reset</li>";
echo "</ol>";
?>
