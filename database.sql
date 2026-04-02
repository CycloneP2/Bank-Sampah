-- Bank Sampah Database Schema for MySQL (XAMPP)
-- Database: bank_sampah

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

DROP TABLE IF EXISTS `penjemputan`;
DROP TABLE IF EXISTS `notifikasi`;
DROP TABLE IF EXISTS `berita`;
DROP TABLE IF EXISTS `transaksi`;
DROP TABLE IF EXISTS `jenis_sampah`;
DROP TABLE IF EXISTS `users`;

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role` enum('nasabah','petugas','pengurus') NOT NULL,
  `saldo` decimal(15,2) DEFAULT 0.00,
  `totalSetoran` decimal(15,2) DEFAULT 0.00,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggalBergabung` date DEFAULT NULL,
  `penimbanganPertama` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `telepon`, `alamat`, `role`, `saldo`, `totalSetoran`, `createdAt`, `tanggalBergabung`, `penimbanganPertama`) VALUES
('ADMIN001', 'Admin Pengurus', 'pengurus@banksampah.com', 'pengurus123', '081122334455', 'Kantor Bank Sampah BPI Lestari', 'pengurus', 0.00, 0.00, '2024-01-01 08:00:00', '2024-01-01', NULL),
('STAFF001', 'Staff Petugas', 'petugas@banksampah.com', 'petugas123', '081122334466', 'Gudang Bank Sampah BPI Lestari', 'petugas', 0.00, 0.00, '2024-01-01 08:00:00', '2024-01-01', NULL),
('NS001', 'Ahmad Wijaya', 'nasabah@banksampah.com', 'nasabah123', '081234567890', 'Jl. Mawar No. 12', 'nasabah', 250500.00, 450000.00, '2024-02-15 10:00:00', '2024-02-15', '2024-02-20'),
('NS002', 'Siti Aminah', 'siti@gmail.com', 'nasabah123', '081299998888', 'Perumahan Elok Blok A1', 'nasabah', 125000.00, 300000.00, '2024-03-01 11:00:00', '2024-03-01', '2024-03-05'),
('NS003', 'Budi Santoso', 'budi@yahoo.com', 'nasabah123', '087811112222', 'Jl. Melati No. 5', 'nasabah', 75000.00, 150000.00, '2024-03-10 09:00:00', '2024-03-10', '2024-03-15');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_sampah`
--

CREATE TABLE `jenis_sampah` (
  `id` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `hargaBeli` decimal(10,2) NOT NULL,
  `hargaJual` decimal(10,2) NOT NULL,
  `kategori` enum('organik','anorganik','B3','lainnya') NOT NULL,
  `deskripsi` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenis_sampah`
--

INSERT INTO `jenis_sampah` (`id`, `nama`, `hargaBeli`, `hargaJual`, `kategori`, `deskripsi`) VALUES
('PL01', 'Botol PET Bersih', 2500.00, 3500.00, 'anorganik', 'Botol plastik PET transparan bersih'),
('PL02', 'Botol PET Kotor', 1500.00, 2500.00, 'anorganik', 'Botol plastik PET dengan label/sisa minuman'),
('PL04', 'Galon PC', 3000.00, 5000.00, 'anorganik', 'Galon air mineral bahan Polycarbonate'),
('PL05', 'Gelas PP Bersih', 2000.00, 3000.00, 'anorganik', 'Gelas plastik PP bening bersih'),
('KK02', 'HVS/Putihan', 2500.00, 3500.00, 'anorganik', 'Kertas HVS putih bersih'),
('KK04', 'Kardus', 1800.00, 2800.00, 'anorganik', 'Kardus coklat bersih'),
('LO01', 'Aluminium', 12000.00, 15000.00, 'anorganik', 'Barang berbahan aluminium'),
('LO02', 'Besi', 3500.00, 5000.00, 'anorganik', 'Besi tua/rongsok'),
('KC02', 'Btl Kc Bening', 500.00, 1000.00, 'anorganik', 'Botol kaca bening/transparan'),
('LL03', 'Jelantah', 3000.00, 5000.00, 'anorganik', 'Minyak goreng bekas pakai (per liter)');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` varchar(50) NOT NULL,
  `nasabahId` varchar(50) DEFAULT NULL,
  `nasabahNama` varchar(100) DEFAULT NULL,
  `jenisTransaksi` enum('setor','tarik','jual') NOT NULL,
  `jenisSampahId` varchar(10) DEFAULT NULL,
  `jenisSampahNama` varchar(100) DEFAULT NULL,
  `berat` decimal(10,2) DEFAULT NULL,
  `hargaPerKg` decimal(10,2) DEFAULT NULL,
  `hargaJualPerKg` decimal(10,2) DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('pending','success','cancelled') DEFAULT 'pending',
  `keterangan` text DEFAULT NULL,
  `petugasId` varchar(50) DEFAULT NULL,
  `petugasNama` varchar(100) DEFAULT NULL,
  `lapakNama` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nasabahId` (`nasabahId`),
  KEY `jenisSampahId` (`jenisSampahId`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`nasabahId`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`jenisSampahId`) REFERENCES `jenis_sampah` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `nasabahId`, `nasabahNama`, `jenisTransaksi`, `jenisSampahId`, `jenisSampahNama`, `berat`, `hargaPerKg`, `hargaJualPerKg`, `jumlah`, `tanggal`, `status`, `keterangan`, `petugasId`, `petugasNama`, `lapakNama`) VALUES
('TRX001', 'NS001', 'Ahmad Wijaya', 'setor', 'PL01', 'Botol PET Bersih', 5.00, 2500.00, 3500.00, 12500.00, '2024-03-20', 'success', 'Setoran rutin', 'STAFF001', 'Staff Petugas', NULL),
('TRX002', 'NS001', 'Ahmad Wijaya', 'setor', 'KK04', 'Kardus', 10.00, 1800.00, 2800.00, 18000.00, '2024-03-21', 'success', 'Kardus bekas pindahan', 'STAFF001', 'Staff Petugas', NULL),
('TRX003', 'NS002', 'Siti Aminah', 'setor', 'LO01', 'Aluminium', 2.00, 12000.00, 15000.00, 24000.00, '2024-03-22', 'success', 'Kaleng soda', 'STAFF001', 'Staff Petugas', NULL),
('TRX004', 'NS001', 'Ahmad Wijaya', 'tarik', NULL, NULL, NULL, NULL, NULL, 5000.00, '2024-03-25', 'success', 'Penarikan uang jajan', 'ADMIN001', 'Admin Pengurus', NULL),
('TRX005', 'NS003', 'Budi Santoso', 'setor', 'PL04', 'Galon PC', 3.00, 3000.00, 5000.00, 9000.00, '2024-03-26', 'pending', 'Belum ditimbang total', 'STAFF001', 'Staff Petugas', NULL),
('TRX006', NULL, NULL, 'jual', 'PL01', 'Botol PET Bersih', 50.00, 2500.00, 3500.00, 175000.00, '2024-03-28', 'success', 'Jual ke Lapak Berkah', 'ADMIN001', 'Admin Pengurus', 'Lapak Berkah');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` varchar(50) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` enum('edukasi','kegiatan','pengumuman') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `tanggal`, `deskripsi`, `gambar`, `kategori`) VALUES
('NEWS001', 'Workshop Pengolahan Kompos', '2024-04-05', 'Mari belajar mengolah sampah organik menjadi pupuk kompos yang berguna.', 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=1000', 'edukasi'),
('NEWS002', 'Lomba Kebersihan Lingkungan', '2024-04-10', 'Persiapkan RT anda untuk mengikuti lomba kebersihan lingkungan tahunan.', 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?q=80&w=1000', 'kegiatan'),
('NEWS003', 'Jadwal Penjemputan Lebaran', '2024-04-15', 'Informasi perubahan jadwal penjemputan sampah selama libur lebaran.', 'https://images.unsplash.com/photo-1621451537084-482c73073a0f?q=80&w=1000', 'pengumuman');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `msg` text NOT NULL,
  `status` enum('Draft','Published') DEFAULT 'Draft',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `title`, `msg`, `status`, `createdAt`) VALUES
('NOTIF001', 'Saldo Masuk', 'Setoran TRX001 Berhasil. Saldo bertambah Rp 12.500.', 'Published', '2024-03-20 10:30:00'),
('NOTIF002', 'Penarikan Sukses', 'Penarikan dana Rp 5.000 sukses.', 'Published', '2024-03-25 14:00:00'),
('NOTIF003', 'Promo Tukar Sampah', 'Tukarkan botol PET minimal 10kg dapatkan bonus saldo Rp 5.000.', 'Published', '2024-03-30 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `penjemputan`
--

CREATE TABLE `penjemputan` (
  `id` varchar(50) NOT NULL,
  `nasabahId` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` enum('pagi','siang','sore') NOT NULL,
  `alamat` text NOT NULL,
  `status` enum('pending','processed','cancelled') DEFAULT 'pending',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `nasabahId` (`nasabahId`),
  CONSTRAINT `penjemputan_ibfk_1` FOREIGN KEY (`nasabahId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penjemputan`
--

INSERT INTO `penjemputan` (`id`, `nasabahId`, `tanggal`, `waktu`, `alamat`, `status`, `createdAt`) VALUES
('PICK001', 'NS001', '2024-04-02', 'pagi', 'Jl. Mawar No. 12', 'processed', '2024-04-01 15:00:00'),
('PICK002', 'NS002', '2024-04-03', 'siang', 'Perumahan Elok Blok A1', 'pending', '2024-04-01 16:30:00'),
('PICK003', 'NS003', '2024-04-04', 'sore', 'Jl. Melati No. 5', 'pending', '2024-04-02 09:00:00');

COMMIT;


