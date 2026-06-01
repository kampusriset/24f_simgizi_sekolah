<?php
require 'config/database.php';
$db = (new Database())->connect();

$newPassword = password_hash("admin123", PASSWORD_DEFAULT);
$stmt = $db->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
$stmt->bind_param("s", $newPassword);

if ($stmt->execute()) {
    echo "Password berhasil diupdate!<br>";
    echo "Username: <b>admin</b><br>";
    echo "Password: <b>admin123</b>";
} else {
    echo "Gagal: " . $db->error;
}
