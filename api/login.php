<?php
// api/login.php - Handler untuk login pengguna

require_once 'config.php';

$data = getJsonInput();

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    try {
        $stmt = $conn->prepare("SELECT id, nama, email, role, saldo, totalSetoran, createdAt, tanggalBergabung, penimbanganPertama 
                                FROM users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password); // Idealnya dicheck menggunakan password_verify jika sdh ter-hash
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(["status" => "success", "data" => $user]);
        } else {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Email atau password salah."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Lengkapi email dan password."]);
}
?>
