import type { User, JenisSampah, Transaksi, Notifikasi, Berita, Penjemputan } from '../types';

export const MOCK_USERS: Record<string, User> = {
  'pengurus@banksampah.com': {
    id: 'ADMIN001',
    nama: 'Admin Pengurus (Demo)',
    email: 'pengurus@banksampah.com',
    role: 'pengurus',
    saldo: 0,
    totalSetoran: 0,
    telepon: '081122334455',
    alamat: 'Kantor Bank Sampah BPI Lestari',
    tanggalBergabung: '2024-01-01',
    createdAt: '2024-01-01 08:00:00'
  } as User,
  'petugas@banksampah.com': {
    id: 'STAFF001',
    nama: 'Staff Petugas (Demo)',
    email: 'petugas@banksampah.com',
    role: 'petugas',
    saldo: 0,
    totalSetoran: 0,
    telepon: '081122334466',
    alamat: 'Gudang Bank Sampah BPI Lestari',
    tanggalBergabung: '2024-01-01',
    createdAt: '2024-01-01 08:00:00'
  } as User,
  'nasabah@banksampah.com': {
    id: 'NS001',
    nama: 'Ahmad Wijaya (Demo)',
    email: 'nasabah@banksampah.com',
    role: 'nasabah',
    saldo: 250500,
    totalSetoran: 450000,
    telepon: '081234567890',
    alamat: 'Jl. Mawar No. 12',
    tanggalBergabung: '2024-02-15',
    createdAt: '2024-02-15 10:00:00',
    penimbanganPertama: '2024-02-20'
  } as User
};

export const MOCK_NASABAH_LIST: User[] = [
  MOCK_USERS['nasabah@banksampah.com'],
  { id: 'NS002', nama: 'Siti Aminah', email: 'siti@gmail.com', role: 'nasabah', saldo: 125000, totalSetoran: 300000, telepon: '081299998888', alamat: 'Perumahan Elok Blok A1', tanggalBergabung: '2024-03-01', createdAt: '2024-03-01 11:00:00' } as User,
  { id: 'NS003', nama: 'Budi Santoso', email: 'budi@yahoo.com', role: 'nasabah', saldo: 75000, totalSetoran: 150000, telepon: '087811112222', alamat: 'Jl. Melati No. 5', tanggalBergabung: '2024-03-10', createdAt: '2024-03-10 09:00:00' } as User,
];

export const MOCK_WASTE_TYPES: JenisSampah[] = [
  { id: 'PL01', nama: 'Botol PET Bersih', hargaBeli: 2500, hargaJual: 3500, kategori: 'anorganik', deskripsi: 'Botol plastik PET transparan bersih' },
  { id: 'PL02', nama: 'Botol PET Kotor', hargaBeli: 1500, hargaJual: 2500, kategori: 'anorganik', deskripsi: 'Botol plastik PET dengan label/sisa minuman' },
  { id: 'PL04', nama: 'Galon PC', hargaBeli: 3000, hargaJual: 5000, kategori: 'anorganik', deskripsi: 'Galon air mineral bahan Polycarbonate' },
  { id: 'KK02', nama: 'HVS/Putihan', hargaBeli: 2500, hargaJual: 3500, kategori: 'anorganik', deskripsi: 'Kertas HVS putih bersih' },
  { id: 'KK04', nama: 'Kardus', hargaBeli: 1800, hargaJual: 2800, kategori: 'anorganik', deskripsi: 'Kardus coklat bersih' },
  { id: 'LO01', nama: 'Aluminium', hargaBeli: 12000, hargaJual: 15000, kategori: 'anorganik', deskripsi: 'Barang berbahan aluminium' },
  { id: 'LL03', nama: 'Jelantah', hargaBeli: 3000, hargaJual: 5000, kategori: 'anorganik', deskripsi: 'Minyak goreng bekas pakai (per liter)' }
];

export const MOCK_TRANSACTIONS: Transaksi[] = [
  { 
    id: 'TRX001', nasabahId: 'NS001', nasabahNama: 'Ahmad Wijaya', jenisTransaksi: 'setor', 
    jenisSampahId: 'PL01', jenisSampahNama: 'Botol PET Bersih', berat: 5.0, 
    hargaPerKg: 2500, jumlah: 12500, tanggal: '2024-03-20', status: 'success', 
    keterangan: 'Setoran rutin', petugasId: 'STAFF001', petugasNama: 'Staff Petugas' 
  },
  { 
    id: 'TRX002', nasabahId: 'NS001', nasabahNama: 'Ahmad Wijaya', jenisTransaksi: 'setor', 
    jenisSampahId: 'KK04', jenisSampahNama: 'Kardus', berat: 10.0, 
    hargaPerKg: 1800, jumlah: 18000, tanggal: '2024-03-21', status: 'success', 
    keterangan: 'Kardus bekas pindahan', petugasId: 'STAFF001', petugasNama: 'Staff Petugas' 
  },
  { 
    id: 'TRX003', nasabahId: 'NS002', nasabahNama: 'Siti Aminah', jenisTransaksi: 'setor', 
    jenisSampahId: 'LO01', jenisSampahNama: 'Aluminium', berat: 2.0, 
    hargaPerKg: 12000, jumlah: 24000, tanggal: '2024-03-22', status: 'success', 
    petugasId: 'STAFF001', petugasNama: 'Staff Petugas' 
  },
  { 
    id: 'TRX004', nasabahId: 'NS001', nasabahNama: 'Ahmad Wijaya', jenisTransaksi: 'tarik', 
    jumlah: 5000, tanggal: '2024-03-25', status: 'success', 
    keterangan: 'Penarikan uang jajan', petugasId: 'ADMIN001', petugasNama: 'Admin Pengurus' 
  },
  { 
    id: 'TRX005', nasabahId: 'NS003', nasabahNama: 'Budi Santoso', jenisTransaksi: 'setor', 
    jenisSampahId: 'PL04', jenisSampahNama: 'Galon PC', berat: 3.0, 
    hargaPerKg: 3000, jumlah: 9000, tanggal: '2024-03-26', status: 'pending', 
    keterangan: 'Belum ditimbang total', petugasId: 'STAFF001', petugasNama: 'Staff Petugas' 
  }
];

export const MOCK_STATS = {
  totalNasabah: 45,
  totalSampahTerkumpul: 1250.5,
  totalSaldoNasabah: 8500000,
  transaksiBulanIni: 128,
  sampahBulanIni: 320.5,
  keuanganBankSampah: 15000000,
  chartData: [
    { bulan: 'Jan', setoran: 40000, penarikan: 20000 },
    { bulan: 'Feb', setoran: 30000, penarikan: 15000 },
    { bulan: 'Mar', setoran: 60000, penarikan: 30000 },
    { bulan: 'Apr', setoran: 80000, penarikan: 40000 }
  ]
};

export const MOCK_NOTIFICATIONS: Notifikasi[] = [
  { id: 'NOTIF001', title: 'Saldo Masuk', msg: 'Setoran TRX001 Berhasil. Saldo bertambah Rp 12.500.', status: 'Published', createdAt: '2024-03-20 10:30:00' },
  { id: 'NOTIF002', title: 'Penarikan Sukses', msg: 'Penarikan dana Rp 5.000 sukses.', status: 'Published', createdAt: '2024-03-25 14:00:00' },
  { id: 'NOTIF003', title: 'Promo Tukar Sampah', msg: 'Tukarkan botol PET minimal 10kg dapatkan bonus saldo Rp 5.000.', status: 'Published', createdAt: '2024-03-30 08:00:00' }
];

export const MOCK_NEWS: Berita[] = [
  { id: 'NEWS001', judul: 'Workshop Pengolahan Kompos', tanggal: '2024-04-05', deskripsi: 'Mari belajar mengolah sampah organik menjadi pupuk kompos yang berguna.', gambar: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=1000', kategori: 'edukasi' },
  { id: 'NEWS002', judul: 'Lomba Kebersihan Lingkungan', tanggal: '2024-04-10', deskripsi: 'Persiapkan RT anda untuk mengikuti lomba kebersihan lingkungan tahunan.', gambar: 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?q=80&w=1000', kategori: 'kegiatan' },
  { id: 'NEWS003', judul: 'Jadwal Penjemputan Lebaran', tanggal: '2024-04-15', deskripsi: 'Informasi perubahan jadwal penjemputan sampah selama libur lebaran.', gambar: 'https://images.unsplash.com/photo-1621451537084-482c73073a0f?q=80&w=1000', kategori: 'pengumuman' }
];

export const MOCK_PICKUPS: Penjemputan[] = [
  { id: 'PICK001', nasabahId: 'NS001', tanggal: '2024-04-02', waktu: 'pagi', alamat: 'Jl. Mawar No. 12', status: 'processed', createdAt: '2024-04-01 15:00:00' },
  { id: 'PICK002', nasabahId: 'NS002', tanggal: '2024-04-03', waktu: 'siang', alamat: 'Perumahan Elok Blok A1', status: 'pending', createdAt: '2024-04-01 16:30:00' },
  { id: 'PICK003', nasabahId: 'NS003', tanggal: '2024-04-04', waktu: 'sore', alamat: 'Jl. Melati No. 5', status: 'pending', createdAt: '2024-04-02 09:00:00' }
];
