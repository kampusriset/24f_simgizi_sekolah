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
 $username = ""; // Diinisiasi kosong untuk ditampilkan kembali di form jika error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Username dan password tidak boleh kosong";
    } else {
        $user = $userModel->login($username);

        // UBAH HANYA BARIS INI: Ganti password_verify dengan perbandingan langsung (===)
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../dashboard.php");
            exit();
        } else {
            $error = "Username atau password salah";
        }
    }
}

// Memanggil file tampilan (View) dan mengirimkan variabel $error serta $username
require 'login_view.php';
?>