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
$data = null;

if (isset($_GET['cari']) && !empty(trim($_GET['cari']))) {
    $cari = trim($_GET['cari']);
    $data = $sekolah->search($cari);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cari Sekolah - SIM Gizi</title>
</head>
<body>
    <h2>Cari Sekolah</h2>

    <form method="GET" action="">
        <input type="text" name="cari" placeholder="Cari nama sekolah..." value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>" required>
        <button type="submit">Cari</button>
    </form>

    <br>

    <?php if ($data && $data->num_rows > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama Sekolah</th>
                <th>Alamat</th>
                <th>Jenjang</th>
                <th>Aksi</th>
            </tr>
            <?php $no = 1; while ($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_sekolah']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td><?= htmlspecialchars($row['jenjang']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id_sekolah'] ?>">Edit</a> |
                    <a href="hapus.php?id=<?= $row['id_sekolah'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php elseif (isset($_GET['cari'])): ?>
        <p>Tidak ditemukan hasil untuk "<?= htmlspecialchars($_GET['cari']) ?>"</p>
    <?php endif; ?>

    <br>
    <a href="index.php">Kembali ke Daftar Sekolah</a>
</body>
</html>
