<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/database.php';
require '../models/Sekolah.php';

$db = (new Database())->connect();
$sekolah = new Sekolah($db);
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_sekolah = trim($_POST['nama_sekolah'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $jenjang = trim($_POST['jenjang'] ?? '');

    if (empty($nama_sekolah) || empty($alamat) || empty($jenjang)) {
        $error = "Semua field harus diisi!";
    } else {
        if ($sekolah->create($nama_sekolah, $alamat, $jenjang)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Gagal menambahkan data!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Sekolah - SIM Gizi</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    background:#f3f4f8;
}

/* Header */
.navbar{
    background:#fff;
    padding:25px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #ddd;
}

.logo{
    font-size:24px;
    font-weight:bold;
    color:#4f46e5;
}

.user{
    color:#6b7280;
}

/* Container */
.container{
    max-width:1200px;
    margin:40px auto;
    padding:0 20px;
}

.page-title{
    font-size:48px;
    font-weight:bold;
    color:#1f2937;
}

.subtitle{
    color:#6b7280;
    margin-top:10px;
    margin-bottom:30px;
}

/* Card */
.card{
    background:#fff;
    padding:40px;
    border-radius:20px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

/* Error */
.alert{
    background:#fee2e2;
    color:#b91c1c;
    padding:12px;
    border-radius:10px;
    margin-bottom:20px;
}

/* Form */
.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#374151;
}

.form-control{
    width:100%;
    padding:14px;
    border:1px solid #d1d5db;
    border-radius:10px;
    font-size:15px;
}

.form-control:focus{
    outline:none;
    border-color:#4f46e5;
}

/* Button */
.btn-group{
    margin-top:25px;
    display:flex;
    gap:10px;
}

.btn{
    padding:12px 25px;
    border:none;
    border-radius:10px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    text-decoration:none;
}

.btn-save{
    background:#4f46e5;
    color:white;
}

.btn-save:hover{
    background:#4338ca;
}

.btn-cancel{
    background:#10b981;
    color:white;
}

.btn-cancel:hover{
    background:#059669;
}
</style>

</head>
<body>

<div class="navbar">
    <div class="logo">SIM Gizi</div>
    <div class="user">
        Halo, <?= htmlspecialchars($_SESSION['user']) ?>
    </div>
</div>

<div class="container">

    <h1 class="page-title">Tambah Sekolah</h1>
    <p class="subtitle">Tambahkan data sekolah baru ke sistem</p>

    <div class="card">

        <?php if ($error): ?>
            <div class="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Nama Sekolah</label>
                <input type="text" name="nama_sekolah" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>Jenjang</label>
                <select name="jenjang" class="form-control" required>
                    <option value="">-- Pilih Jenjang --</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-save">
                    Simpan
                </button>

                <a href="index.php" class="btn btn-cancel">
                    Kembali
                </a>
            </div>

        </form>

    </div>

</div>

</body>
</html>