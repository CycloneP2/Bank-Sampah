<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $nasabahId = $_GET['nasabahId'] ?? null;
    try {
        if ($nasabahId) {
            $stmt = $conn->prepare("SELECT * FROM notifikasi WHERE nasabahId IS NULL OR nasabahId = ? ORDER BY createdAt DESC");
            $stmt->execute([$nasabahId]);
        } else {
            $stmt = $conn->query("SELECT * FROM notifikasi ORDER BY createdAt DESC");
        }
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "data" => $res]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    $data = getJsonInput();
    try {
        $stmt = $conn->prepare("INSERT INTO notifikasi (nasabahId, judul, pesan, tipe) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['nasabahId'] ?? null,
            $data['judul'],
            $data['pesan'],
            $data['tipe'] ?? 'info'
        ]);
        echo json_encode(["status" => "success", "message" => "Notifikasi terkirim"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID diperlukan."]);
        exit;
    }
    try {
        $stmt = $conn->prepare("DELETE FROM notifikasi WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["status" => "success", "message" => "Notifikasi dihapus."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
