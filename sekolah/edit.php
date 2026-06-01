<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../config/database.php';
require '../models/Sekolah.php';

$db = (new Database())->connect();
$sekolah = new Sekolah($db);

$id = intval($_GET['id'] ?? 0);
$row = $sekolah->getById($id);

if (!$row) {
    die("Data sekolah tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_sekolah'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $jenjang = trim($_POST['jenjang'] ?? '');

    if (empty($nama) || empty($alamat) || empty($jenjang)) {
        $error = "Semua field harus diisi!";
    } else {
        if ($sekolah->update($id, $nama, $alamat, $jenjang)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Gagal mengupdate data!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sekolah - SIM Gizi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        /* ── Navbar ── */
        .navbar-sim {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-sim .brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: #3d3df5;
            text-decoration: none;
            letter-spacing: -0.3px;
        }

        .navbar-sim .user-info {
            font-size: 0.95rem;
            color: #6c757d;
        }

        /* ── Card ── */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px;
        }

        .btn {
            border-radius: 10px;
            padding: 10px 25px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar-sim">
    <a href="../sekolah/index.php" class="brand">SIM Gizi</a>
    <span class="user-info">Halo, <?= htmlspecialchars($_SESSION['user']['nama'] ?? $_SESSION['user'] ?? 'admin') ?></span>
</nav>

<!-- Konten -->
<div class="container py-5">

    <div class="mb-4">
        <h1 class="fw-bold display-5">Edit Sekolah</h1>
        <p class="text-muted">Perbarui data sekolah pada sistem</p>
    </div>

    <div class="card">
        <div class="card-body p-5">

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-4">
                    <label class="form-label fw-semibold">Nama Sekolah</label>
                    <input
                        type="text"
                        name="nama_sekolah"
                        class="form-control"
                        value="<?= htmlspecialchars($row['nama_sekolah'] ?? '') ?>"
                        required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea
                        name="alamat"
                        rows="4"
                        class="form-control"
                        required><?= htmlspecialchars($row['alamat'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Jenjang</label>
                    <select name="jenjang" class="form-select" required>
                        <option value="">-- Pilih Jenjang --</option>
                        <option value="SD"  <?= ($row['jenjang'] == 'SD')  ? 'selected' : '' ?>>SD</option>
                        <option value="SMP" <?= ($row['jenjang'] == 'SMP') ? 'selected' : '' ?>>SMP</option>
                        <option value="SMA" <?= ($row['jenjang'] == 'SMA') ? 'selected' : '' ?>>SMA</option>
                        <option value="SMK" <?= ($row['jenjang'] == 'SMK') ? 'selected' : '' ?>>SMK</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="index.php" class="btn btn-success ms-2">Kembali</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
