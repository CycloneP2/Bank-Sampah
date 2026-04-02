<?php
// api/nasabah.php - Kelola data nasabah

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Ambil data nasabah spesifik jika ada ID, jika tdk ada ambil semua
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id AND role = 'nasabah'");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "data" => $res]);
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE role = 'nasabah'");
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "data" => $res]);
    }
} elseif ($method === 'POST') {
    // Tambah nasabah baru
    $input = getJsonInput();
    if (isset($input['id'], $input['nama'], $input['email'], $input['role'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO users (id, nama, email, password, telepon, alamat, role, tanggalBergabung) 
                                    VALUES (:id, :nama, :email, :password, :telepon, :alamat, :role, :tanggalBergabung)");
            
            // Set password default as id nasabah if missing or as requested
            $password = isset($input['password']) ? $input['password'] : $input['id'];
            $tanggalBergabung = isset($input['tanggalBergabung']) ? $input['tanggalBergabung'] : date('Y-m-d');
            
            $stmt->bindParam(':id', $input['id']);
            $stmt->bindParam(':nama', $input['nama']);
            $stmt->bindParam(':email', $input['email']);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':telepon', $input['telepon']);
            $stmt->bindParam(':alamat', $input['alamat']);
            $stmt->bindParam(':role', $input['role']);
            $stmt->bindParam(':tanggalBergabung', $tanggalBergabung);
            
            $stmt->execute();
            echo json_encode(["status" => "success", "message" => "Nasabah berhasil ditambahkan."]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
} elseif ($method === 'PUT') {
    // Update data nasabah
    $input = getJsonInput();
    if (isset($input['id'])) {
        try {
            $id = $input['id'];
            // Dynamic query building
            $updates = [];
            $params = [':id' => $id];
            
            $fields = ['nama', 'email', 'telepon', 'alamat', 'rekening_bank', 'nomor_rekening', 'nama_rekening'];
            foreach ($fields as $field) {
                if (isset($input[$field])) {
                    $updates[] = "$field = :$field";
                    $params[":$field"] = $input[$field];
                }
            }
            
            if (empty($updates)) {
                throw new Exception("Tidak ada field yang diupdate.");
            }
            
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            
            echo json_encode(["status" => "success", "message" => "Profil berhasil diperbarui."]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID Nasabah diperlukan."]);
    }
}
?>
