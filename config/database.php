<?php

class Database {  // ✅ Huruf kapital (konvensi PHP)
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db   = "sim_gizi";

    public function connect() {
        $conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

        // ✅ Tambahkan pengecekan koneksi
        if ($conn->connect_error) {
            die("Koneksi database gagal: " . $conn->connect_error);
        }

        return $conn;
    }
}
?>