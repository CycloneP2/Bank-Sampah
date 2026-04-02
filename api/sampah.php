<?php
// api/sampah.php - Master data jenis sampah

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $conn->query("SELECT * FROM jenis_sampah ORDER BY id ASC");

        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert prices to numeric
        foreach ($res as &$row) {
            $row['hargaBeli'] = (float)$row['hargaBeli'];
            $row['hargaJual'] = (float)$row['hargaJual'];
        }
        
        echo json_encode(["status" => "success", "data" => $res]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
