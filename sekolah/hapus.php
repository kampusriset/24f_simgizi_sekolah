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

$id = intval($_GET['id'] ?? 0);
$sekolah->delete($id);

header("Location: index.php");
exit();
