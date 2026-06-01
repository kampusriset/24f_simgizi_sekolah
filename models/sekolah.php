<?php

class Sekolah {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($limit, $offset, $sort) {
    $allowedSort = [
        'id_sekolah ASC', 'id_sekolah DESC',
        'nama_sekolah ASC', 'nama_sekolah DESC',
        'jenjang ASC', 'jenjang DESC'
    ];

    if (!in_array($sort, $allowedSort)) {
        $sort = 'id_sekolah DESC';
    }

    $stmt = $this->conn->prepare("SELECT * FROM sekolah ORDER BY $sort LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    return $stmt->get_result();
    }

    public function count() {
        $res = $this->conn->query("SELECT COUNT(*) as total FROM sekolah");
        return $res->fetch_assoc()['total'];
    }

    public function create($nama_sekolah, $alamat, $jenjang) {
        $stmt = $this->conn->prepare("INSERT INTO sekolah(nama_sekolah, alamat, jenjang) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $nama_sekolah, $alamat, $jenjang);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM sekolah WHERE id_sekolah = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM sekolah WHERE id_sekolah = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id, $nama_sekolah, $alamat, $jenjang) {
        $stmt = $this->conn->prepare("UPDATE sekolah SET nama_sekolah = ?, alamat = ?, jenjang = ? WHERE id_sekolah = ?");
        $stmt->bind_param("sssi", $nama_sekolah, $alamat, $jenjang, $id);
        return $stmt->execute();
    }

    public function search($keyword) {
        $keyword = "%" . $keyword . "%";
        $stmt = $this->conn->prepare("SELECT * FROM sekolah WHERE nama_sekolah LIKE ?");
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
