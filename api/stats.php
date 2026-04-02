<?php
// api/stats.php - Hitung statistik untuk dashboard

require_once 'config.php';

try {
    // 1. Total Nasabah
    $stmt = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'nasabah'");
    $totalNasabah = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 2. Total Sampah Terkumpul (Berat)
    $stmt = $conn->query("SELECT SUM(berat) as total FROM transaksi WHERE jenisTransaksi = 'setor' AND status = 'success'");
    $totalSampah = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // 3. Total Saldo Seluruh Nasabah
    $stmt = $conn->query("SELECT SUM(saldo) as total FROM users WHERE role = 'nasabah'");
    $totalSaldo = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // 4. Transaksi Bulan Ini
    $stmt = $conn->query("SELECT COUNT(*) as total FROM transaksi WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
    $transaksiBulanIni = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // 5. Sampah Bulan Ini
    $stmt = $conn->query("SELECT SUM(berat) as total FROM transaksi WHERE jenisTransaksi = 'setor' AND status = 'success' AND MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
    $sampahBulanIni = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    
    // 6. Keuangan Bank (Laba kotor: sum(hargaJual - hargaBeli) * berat)
    $stmt = $conn->query("SELECT SUM((hargaJualPerKg - hargaPerKg) * berat) as total FROM transaksi WHERE jenisTransaksi = 'setor' AND status = 'success'");
    $keuanganBank = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // 7. Data Chart (6 Bulan Terakhir)
    $chartData = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = date('m', strtotime("-$i month"));
        $year = date('Y', strtotime("-$i month"));
        $monthName = date('M', strtotime("-$i month"));
        
        $stmt = $conn->prepare("SELECT SUM(berat) as total FROM transaksi WHERE jenisTransaksi = 'setor' AND status = 'success' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?");
        $stmt->execute([$month, $year]);
        $val = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        $chartData[] = ["name" => $monthName, "transaksi" => (float)$val];
    }

    echo json_encode([
        "status" => "success",
        "data" => [
            "totalNasabah" => (int)$totalNasabah,
            "totalSampahTerkumpul" => (float)$totalSampah,
            "totalSaldoNasabah" => (float)$totalSaldo,
            "transaksiBulanIni" => (int)$transaksiBulanIni,
            "sampahBulanIni" => (float)$sampahBulanIni,
            "keuanganBankSampah" => (float)$keuanganBank,
            "chartData" => $chartData
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
