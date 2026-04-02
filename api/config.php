<?php
// api/config.php - File konfigurasi database untuk MySQL XAMPP

// Izinkan akses dari frontend (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// AKTIFKAN error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Konfigurasi Database
$host = "localhost";
$db_name = "bank_sampah";
$username = "root"; // Default XAMPP
$password = "";     // Default XAMPP

try {
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8");
} catch(PDOException $exception) {
    echo json_encode(["status" => "error", "message" => "Connection error: " . $exception->getMessage()]);
    exit;
}

// Helper untuk mengambil input JSON dari body request
function getJsonInput() {
    return json_decode(file_get_contents("php://input"), true);
}

// Handle request OPTIONS (Preflight untuk CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>
