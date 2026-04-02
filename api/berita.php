<?php
// api/berita.php - Kelola berita dan kegiatan

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $conn->query("SELECT * FROM berita ORDER BY tanggal DESC");
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "data" => $res]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $stmt = $conn->prepare("INSERT INTO berita (judul, tanggal, deskripsi, gambar, kategori) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['judul'],
            $data['tanggal'],
            $data['deskripsi'],
            $data['gambar'] ?? '',
            $data['kategori']
        ]);
        echo json_encode(["status" => "success", "message" => "Berita berhasil ditambahkan"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
