<?php
// api/penjemputan.php - Kelola permintaan jemput sampah

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $nasabahId = $_GET['nasabahId'] ?? null;
    try {
        if ($nasabahId) {
            $stmt = $conn->prepare("SELECT * FROM penjemputan WHERE nasabahId = ? ORDER BY tanggal DESC");
            $stmt->execute([$nasabahId]);
        } else {
            $stmt = $conn->query("SELECT * FROM penjemputan ORDER BY tanggal DESC");
        }
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "data" => $res]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $stmt = $conn->prepare("INSERT INTO penjemputan (nasabahId, nasabahNama, tanggal, waktu, alamat, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([
            $data['nasabahId'],
            $data['nasabahNama'],
            $data['tanggal'],
            $data['waktu'],
            $data['alamat']
        ]);
        echo json_encode(["status" => "success", "message" => "Permintaan penjemputan berhasil dikirim"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
