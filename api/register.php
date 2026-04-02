<?php
require_once 'config.php';

$data = getJsonInput();

if (isset($data['nama'], $data['email'], $data['password'], $data['telepon'])) {
    $nama = $data['nama'];
    $email = $data['email'];
    $password = $data['password'];
    $telepon = $data['telepon'];
    $alamat = isset($data['alamat']) ? $data['alamat'] : '';
    
    // Default values for new nasabah
    $role = 'nasabah';
    $id = 'NSB' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    $now = date('Y-m-d H:i:s');
    $joinDate = date('d M Y');

    try {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $check->execute(['email' => $email]);
        if ($check->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Email sudah terdaftar."]);
            exit;
        }

        $sql = "INSERT INTO users (id, nama, email, password, role, telepon, alamat, saldo, totalSetoran, createdAt, tanggalBergabung) 
                VALUES (:id, :nama, :email, :password, :role, :telepon, :alamat, 0, 0, :createdAt, :joinDate)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'nama' => $nama,
            'email' => $email,
            'password' => $password, // Plain text as per login logic
            'role' => $role,
            'telepon' => $telepon,
            'alamat' => $alamat,
            'createdAt' => $now,
            'joinDate' => $joinDate
        ]);

        echo json_encode(["status" => "success", "message" => "Registrasi berhasil.", "id" => $id]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Terjadi kesalahan: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap."]);
}
?>
