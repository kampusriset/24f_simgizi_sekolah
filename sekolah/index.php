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

// Handle pencarian
 $cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
if ($cari !== '') {
    $data = $sekolah->search($cari);
} else {
    $data = $sekolah->getAll(100, 0, "id_sekolah DESC");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Sekolah - SIM Gizi</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f5f6fa;
}

/* Header */
.header{
    background:#fff;
    padding:20px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #e5e7eb;
}

.logo{
    font-size:26px;
    font-weight:700;
    color:#4F46E5;
}

.user{
    color:#6b7280;
}

/* Content */
.container{
    width:90%;
    margin:30px auto;
}

.title{
    margin-bottom:25px;
}

.title h1{
    font-size:35px;
    color:#1f2937;
}

.title p{
    color:#6b7280;
    margin-top:5px;
}

/* Card */
.card{
    background:#fff;
    border-radius:20px;
    padding:25px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

/* Menu */
.menu{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.left-menu{
    display:flex;
    gap:10px;
}

.btn{
    text-decoration:none;
    padding:12px 18px;
    border-radius:12px;
    color:#fff;
    font-weight:600;
    transition:.3s;
}

.btn-primary{
    background:#4F46E5;
}

.btn-primary:hover{
    background:#4338CA;
}

.btn-success{
    background:#10B981;
}

.btn-success:hover{
    background:#059669;
}

.search input{
    width:280px;
    padding:12px 15px;
    border:1px solid #ddd;
    border-radius:12px;
    outline:none;
}

/* Table */
.table-responsive{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#4F46E5;
    color:white;
}

thead th{
    padding:15px;
    text-align:left;
}

tbody td{
    padding:15px;
    border-bottom:1px solid #eee;
}

tbody tr:hover{
    background:#f8f9ff;
}

/* Action */
.edit{
    color:#2563EB;
    text-decoration:none;
    font-weight:600;
}

.hapus{
    color:#DC2626;
    text-decoration:none;
    font-weight:600;
}

.badge{
    background:#EEF2FF;
    color:#4F46E5;
    padding:5px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}
</style>

</head>
<body>

<div class="header">
    <div class="logo">SIM Gizi</div>
    <div class="user">
        Halo, <?= htmlspecialchars($_SESSION['user']) ?>
    </div>
</div>

<div class="container">

    <div class="title">
        <h1>Data Sekolah</h1>
        <p>Kelola seluruh data sekolah yang terdaftar</p>
    </div>

    <div class="card">

        <div class="menu">
            <div class="left-menu">
                <a href="tambah.php" class="btn btn-primary">
                    + Tambah Sekolah
                </a>

                <a href="../dashboard.php" class="btn btn-success">
                    Dashboard
                </a>
            </div>

            <div class="search">
                <form method="GET" action="" style="display:flex;gap:8px;">
                    <input type="text" name="cari" placeholder="Cari sekolah..." value="<?= htmlspecialchars($cari) ?>">
                    <button type="submit" class="btn btn-primary" style="padding:12px 18px;border:none;cursor:pointer;">Cari</button>
                    <?php if ($cari !== ''): ?>
                        <a href="index.php" class="btn btn-success" style="padding:12px 18px;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Sekolah</th>
                        <th>Alamat</th>
                        <th>Jenjang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                if ($data && $data->num_rows > 0) {
                    $no = 1;
                    while ($row = $data->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <?= htmlspecialchars($row['nama_sekolah']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['alamat']) ?>
                        </td>

                        <td>
                            <span class="badge">
                                <?= htmlspecialchars($row['jenjang']) ?>
                            </span>
                        </td>

                        <td>
                            <a class="edit"
                               href="edit.php?id=<?= $row['id_sekolah'] ?>">
                                Edit
                            </a>
                            |
                            <a class="hapus"
                               href="hapus.php?id=<?= $row['id_sekolah'] ?>"
                               onclick="return confirm('Yakin hapus data ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" style="text-align:center;padding:30px;color:#6b7280;">
                            <?php if ($cari !== ''): ?>
                                Tidak ditemukan hasil untuk "<strong><?= htmlspecialchars($cari) ?></strong>"
                            <?php else: ?>
                                Belum ada data sekolah
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>
        </div>

    </div>

</div>

</body>
</html>