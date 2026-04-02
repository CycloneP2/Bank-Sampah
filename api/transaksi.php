<?php
// api/transaksi.php - Kelola riwayat transaksi

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $nasabahId = $_GET['nasabahId'] ?? null;
    $limit = $_GET['limit'] ?? 100;

    try {
        if ($nasabahId) {
            $stmt = $conn->prepare("
                SELECT t.*, u.rekening_bank, u.nomor_rekening, u.nama_rekening 
                FROM transaksi t 
                LEFT JOIN users u ON t.nasabahId = u.id 
                WHERE t.nasabahId = :nasabahId 
                ORDER BY t.tanggal DESC, t.id DESC LIMIT :limit
            ");
            $stmt->bindValue(':nasabahId', $nasabahId);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        } else {
            $stmt = $conn->prepare("
                SELECT t.*, u.rekening_bank, u.nomor_rekening, u.nama_rekening 
                FROM transaksi t 
                LEFT JOIN users u ON t.nasabahId = u.id 
                ORDER BY t.tanggal DESC, t.id DESC LIMIT :limit
            ");
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert numbers
        foreach ($res as &$row) {
            $row['berat'] = (float)$row['berat'];
            $row['hargaPerKg'] = (float)$row['hargaPerKg'];
            $row['jumlah'] = (float)$row['jumlah'];
        }
        
        echo json_encode(["status" => "success", "data" => $res]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else if ($method === 'POST') {
    $data = getJsonInput();
    
    if (!$data) {
        http_response_code(400);
        die(json_encode(["status" => "error", "message" => "Data tidak valid atau body kosong"]));
    }

    $id = 'TRX' . strtoupper(uniqid());
    
    try {
        $sql = "INSERT INTO transaksi (
                    id, nasabahId, nasabahNama, jenisTransaksi, 
                    jenisSampahId, jenisSampahNama, berat, 
                    hargaPerKg, jumlah, tanggal, status, keterangan
                ) VALUES (
                    :id, :nasabahId, :nasabahNama, :jenisTransaksi, 
                    :jenisSampahId, :jenisSampahNama, :berat, 
                    :hargaPerKg, :jumlah, :tanggal, :status, :keterangan
                )";
        
        $stmt = $conn->prepare($sql);
        
        // Sanitize and default values
        $params = [
            ':id' => $id,
            ':nasabahId' => $data['nasabahId'] ?? null,
            ':nasabahNama' => $data['nasabahNama'] ?? null,
            ':jenisTransaksi' => $data['jenisTransaksi'] ?? 'setor',
            ':jenisSampahId' => $data['jenisSampahId'] ?? null,
            ':jenisSampahNama' => $data['jenisSampahNama'] ?? null,
            ':berat' => (float)($data['berat'] ?? 0),
            ':hargaPerKg' => (float)($data['hargaPerKg'] ?? 0),
            ':jumlah' => (float)($data['jumlah'] ?? 0),
            ':tanggal' => $data['tanggal'] ?? date('Y-m-d'),
            ':status' => $data['status'] ?? 'pending',
            ':keterangan' => $data['keterangan'] ?? '',
            ':rekening_tujuan' => $data['rekening_tujuan'] ?? null,
            ':nomor_tujuan' => $data['nomor_tujuan'] ?? null,
            ':nama_tujuan' => $data['nama_tujuan'] ?? null
        ];

        if (!$params[':nasabahId']) {
            throw new Exception("ID Nasabah harus diisi.");
        }
        
        $sql = "INSERT INTO transaksi (
                    id, nasabahId, nasabahNama, jenisTransaksi, 
                    jenisSampahId, jenisSampahNama, berat, 
                    hargaPerKg, jumlah, tanggal, status, keterangan,
                    rekening_tujuan, nomor_tujuan, nama_tujuan
                ) VALUES (
                    :id, :nasabahId, :nasabahNama, :jenisTransaksi, 
                    :jenisSampahId, :jenisSampahNama, :berat, 
                    :hargaPerKg, :jumlah, :tanggal, :status, :keterangan,
                    :rekening_tujuan, :nomor_tujuan, :nama_tujuan
                )";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        echo json_encode(["status" => "success", "message" => "Transaksi berhasil disimpan", "id" => $id]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Terjadi kesalahan: " . $e->getMessage()]);
    }
}
 else if ($method === 'PUT') {
    $data = getJsonInput();
    $id = $data['id'] ?? null;
    $status = $data['status'] ?? null; // 'success' or 'rejected'

    if (!$id || !$status) {
        die(json_encode(["status" => "error", "message" => "ID dan status diperlukan"]));
    }

    try {
        $conn->beginTransaction();

        // Ambil data transaksi lama
        $stmt = $conn->prepare("SELECT * FROM transaksi WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $trx = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$trx) throw new Exception("Transaksi tidak ditemukan");
        if ($trx['status'] === 'success') throw new Exception("Transaksi sudah diproses sebelumnya");

        // Update status transaksi
        $processedBy = $data['processed_by'] ?? 'Sistem';
        $stmt = $conn->prepare("UPDATE transaksi SET status = :status, processed_by = :by, processed_at = NOW() WHERE id = :id");
        $stmt->execute([':status' => $status, ':id' => $id, ':by' => $processedBy]);

        // Jika disetujui (success), update saldo nasabah
        if ($status === 'success') {
            $nasabahId = $trx['nasabahId'];
            $jumlah = (float)$trx['jumlah'];
            $jenis = $trx['jenisTransaksi'];

            if ($jenis === 'setor') {
                $stmt = $conn->prepare("UPDATE users SET saldo = saldo + :jumlah WHERE id = :id");
            } else if ($jenis === 'tarik') {
                $stmt = $conn->prepare("UPDATE users SET saldo = saldo - :jumlah WHERE id = :id");
            }
            $stmt->execute([':jumlah' => $jumlah, ':id' => $nasabahId]);
        }

        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Transaksi diperbarui"]);
    } catch (Exception $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>


