-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 05:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pinjam_sbum`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(150) NOT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `stok_tersedia` int(11) DEFAULT 0,
  `foto_barang` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `id_pic` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `kode_barang`, `stok_tersedia`, `foto_barang`, `keterangan`, `id_pic`) VALUES
(1, 'Proyektor', 'BRG001', 5, NULL, 'Proyektor portable', 5),
(2, 'Microphone', 'BRG002', 10, NULL, 'Microphone wireless', 5);

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjaman_barang`
--

CREATE TABLE `detail_peminjaman_barang` (
  `id_detail_peminjaman_barang` int(11) NOT NULL,
  `peminjaman_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_peminjaman_barang`
--

INSERT INTO `detail_peminjaman_barang` (`id_detail_peminjaman_barang`, `peminjaman_id`, `barang_id`, `jumlah`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjaman_ruangan`
--

CREATE TABLE `detail_peminjaman_ruangan` (
  `id_detail_peminjaman_ruangan` int(11) NOT NULL,
  `peminjaman_id` int(11) NOT NULL,
  `ruangan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_peminjaman_ruangan`
--

INSERT INTO `detail_peminjaman_ruangan` (`id_detail_peminjaman_ruangan`, `peminjaman_id`, `ruangan_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pengembalian_barang`
--

CREATE TABLE `detail_pengembalian_barang` (
  `id_detail_pengembalian_barang` int(11) NOT NULL,
  `id_pengembalian_barang` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah_barang_dikembalikan` int(11) NOT NULL DEFAULT 1,
  `kondisi_barang` enum('baik','rusak ringan','rusak berat','hilang') DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pengembalian_ruangan`
--

CREATE TABLE `detail_pengembalian_ruangan` (
  `id_detail_pengembalian_ruangan` int(11) NOT NULL,
  `id_pengembalian_ruangan` int(11) NOT NULL,
  `id_ruangan` int(11) NOT NULL,
  `kondisi_ruangan` enum('baik','rusak ringan','rusak berat') DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas_ruangan`
--

CREATE TABLE `fasilitas_ruangan` (
  `id_fasilitas` int(11) NOT NULL,
  `id_ruangan` int(11) NOT NULL,
  `nama_fasilitas` varchar(150) NOT NULL,
  `jumlah` int(11) DEFAULT 1,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fasilitas_ruangan`
--

INSERT INTO `fasilitas_ruangan` (`id_fasilitas`, `id_ruangan`, `nama_fasilitas`, `jumlah`, `keterangan`) VALUES
(1, 1, 'AC', 2, 'AC ruangan'),
(2, 1, 'Proyektor Tetap', 1, 'Terpasang di plafon');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dosen_id` int(11) DEFAULT NULL,
  `nama_kegiatan` varchar(200) NOT NULL,
  `jumlah_peserta` int(11) DEFAULT NULL,
  `jenis_peminjaman` enum('barang','ruangan','keduanya') NOT NULL,
  `tanggal_pengajuan` datetime DEFAULT current_timestamp(),
  `status` enum('pending','disetujui','ditolak','selesai','dibatalkan') DEFAULT 'pending',
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `user_id`, `dosen_id`, `nama_kegiatan`, `jumlah_peserta`, `jenis_peminjaman`, `tanggal_pengajuan`, `status`, `keterangan`) VALUES
(1, 1, 2, 'Seminar Database', 80, 'keduanya', '2026-04-21 20:15:16', 'pending', 'Kegiatan seminar basis data'),
(2, 13, 2, 'Rapat Koordinasi PBL SBUM TRPL 2A Malam', 6, 'ruangan', '2026-04-21 21:27:01', 'pending', 'Rapat pembahasan ERD dan Normalisasi Database');

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian_barang`
--

CREATE TABLE `pengembalian_barang` (
  `id_pengembalian_barang` int(11) NOT NULL,
  `peminjaman_id` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian_ruangan`
--

CREATE TABLE `pengembalian_ruangan` (
  `id_pengembalian_ruangan` int(11) NOT NULL,
  `peminjaman_id` int(11) NOT NULL,
  `tanggal_pengembalian` date DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id_role`, `nama_role`) VALUES
(1, 'Mahasiswa'),
(2, 'Dosen'),
(3, 'Admin SBUM'),
(4, 'Kepala SBUM'),
(5, 'PIC Fasilitas'),
(6, 'Pamdal');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id_ruangan` int(11) NOT NULL,
  `nama_ruangan` varchar(150) NOT NULL,
  `nama_gedung` varchar(150) DEFAULT NULL,
  `kode_ruangan` varchar(50) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `lantai` varchar(20) DEFAULT NULL,
  `status_ruangan` enum('tersedia','tidak tersedia','maintenance') DEFAULT 'tersedia',
  `foto_ruangan` varchar(255) DEFAULT NULL,
  `id_pic` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `nama_ruangan`, `nama_gedung`, `kode_ruangan`, `kapasitas`, `lantai`, `status_ruangan`, `foto_ruangan`, `id_pic`) VALUES
(1, 'Ruang Seminar 1', 'Gedung Utama', 'R101', 100, '1', 'tersedia', NULL, 5),
(2, 'Ruang Rapat 2', 'Gedung Utama', 'R102', 40, '1', 'tersedia', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `nim`, `nik`, `email`, `password`, `created_at`) VALUES
(1, 'Moch Azmi Aris Sandita', '4342511024', NULL, 'azmi@sbum.ac.id', 'hash_azmi', '2026-04-21 20:15:16'),
(2, 'Dosen Penanggung Jawab', 'D001', NULL, 'dosen@sbum.ac.id', 'hash_dosen', '2026-04-21 20:15:16'),
(3, 'Admin SBUM', 'A001', NULL, 'admin@sbum.ac.id', 'hash_admin', '2026-04-21 20:15:16'),
(4, 'Kepala SBUM', 'K001', NULL, 'kepala@sbum.ac.id', 'hash_kepala', '2026-04-21 20:15:16'),
(5, 'PIC Ruangan', 'P001', NULL, 'pic@sbum.ac.id', 'hash_pic', '2026-04-21 20:15:16'),
(6, 'Petugas Pamdal', 'PM001', NULL, 'pamdal@sbum.ac.id', 'hash_pamdal', '2026-04-21 20:15:16'),
(13, 'sihab', '4342511099', NULL, 'ketua.pbl@student.polibatam.ac.id', 'hash_pbl_123', '2026-04-21 21:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id_user_role` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id_user_role`, `user_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5),
(6, 6, 6),
(7, 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `verifikasi_peminjaman`
--

CREATE TABLE `verifikasi_peminjaman` (
  `id_verifikasi_peminjaman` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_verifikator` int(11) NOT NULL,
  `peran_verifikasi` varchar(100) DEFAULT NULL,
  `jenis_verifikasi` varchar(100) DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verifikasi_peminjaman`
--

INSERT INTO `verifikasi_peminjaman` (`id_verifikasi_peminjaman`, `id_peminjaman`, `id_verifikator`, `peran_verifikasi`, `jenis_verifikasi`, `status`, `catatan`, `tanggal`) VALUES
(1, 1, 2, 'Dosen', 'Persetujuan Akademik', 'disetujui', 'Layak dilaksanakan', '2026-06-08 00:00:00'),
(2, 2, 2, 'Dosen', 'Persetujuan Akademik', 'disetujui', 'Silakan gunakan ruangan dengan baik, jaga kebersihan.', '2026-05-10 10:00:00'),
(3, 2, 3, 'Admin SBUM', 'Verifikasi Operasional', 'pending', 'Jadwal sedang dicek ulang dengan logistik.', '2026-05-10 10:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `verifikasi_pengembalian`
--

CREATE TABLE `verifikasi_pengembalian` (
  `id_verifikasi_pengembalian` int(11) NOT NULL,
  `id_pengembalian_ruangan` int(11) DEFAULT NULL,
  `id_pengembalian_barang` int(11) DEFAULT NULL,
  `id_verifikator` int(11) NOT NULL,
  `peran_verifikasi` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`),
  ADD KEY `fk_barang_pic` (`id_pic`);

--
-- Indexes for table `detail_peminjaman_barang`
--
ALTER TABLE `detail_peminjaman_barang`
  ADD PRIMARY KEY (`id_detail_peminjaman_barang`),
  ADD KEY `fk_detail_barang_peminjaman` (`peminjaman_id`),
  ADD KEY `fk_detail_barang_id` (`barang_id`);

--
-- Indexes for table `detail_peminjaman_ruangan`
--
ALTER TABLE `detail_peminjaman_ruangan`
  ADD PRIMARY KEY (`id_detail_peminjaman_ruangan`),
  ADD KEY `fk_detail_ruangan_peminjaman` (`peminjaman_id`),
  ADD KEY `fk_detail_ruangan_id` (`ruangan_id`);

--
-- Indexes for table `detail_pengembalian_barang`
--
ALTER TABLE `detail_pengembalian_barang`
  ADD PRIMARY KEY (`id_detail_pengembalian_barang`),
  ADD KEY `fk_detail_pengembalian_barang_header` (`id_pengembalian_barang`),
  ADD KEY `fk_detail_pengembalian_barang_id` (`id_barang`);

--
-- Indexes for table `detail_pengembalian_ruangan`
--
ALTER TABLE `detail_pengembalian_ruangan`
  ADD PRIMARY KEY (`id_detail_pengembalian_ruangan`),
  ADD KEY `fk_detail_pengembalian_ruangan` (`id_pengembalian_ruangan`),
  ADD KEY `fk_detail_pengembalian_ruangan_id` (`id_ruangan`);

--
-- Indexes for table `fasilitas_ruangan`
--
ALTER TABLE `fasilitas_ruangan`
  ADD PRIMARY KEY (`id_fasilitas`),
  ADD KEY `fk_fasilitas_ruangan` (`id_ruangan`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `fk_peminjaman_user` (`user_id`),
  ADD KEY `fk_peminjaman_dosen` (`dosen_id`);

--
-- Indexes for table `pengembalian_barang`
--
ALTER TABLE `pengembalian_barang`
  ADD PRIMARY KEY (`id_pengembalian_barang`),
  ADD KEY `fk_pengembalian_barang_peminjaman` (`peminjaman_id`);

--
-- Indexes for table `pengembalian_ruangan`
--
ALTER TABLE `pengembalian_ruangan`
  ADD PRIMARY KEY (`id_pengembalian_ruangan`),
  ADD KEY `fk_pengembalian_ruangan_peminjaman` (`peminjaman_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id_ruangan`),
  ADD UNIQUE KEY `kode_ruangan` (`kode_ruangan`),
  ADD KEY `fk_ruangan_pic` (`id_pic`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id_user_role`),
  ADD UNIQUE KEY `user_id` (`user_id`,`role_id`),
  ADD KEY `fk_user_role_role` (`role_id`);

--
-- Indexes for table `verifikasi_peminjaman`
--
ALTER TABLE `verifikasi_peminjaman`
  ADD PRIMARY KEY (`id_verifikasi_peminjaman`),
  ADD KEY `fk_verif_peminjaman` (`id_peminjaman`),
  ADD KEY `fk_verif_verifikator` (`id_verifikator`);

--
-- Indexes for table `verifikasi_pengembalian`
--
ALTER TABLE `verifikasi_pengembalian`
  ADD PRIMARY KEY (`id_verifikasi_pengembalian`),
  ADD KEY `fk_verif_pengembalian_user` (`id_verifikator`),
  ADD KEY `fk_verif_pengembalian_ruangan` (`id_pengembalian_ruangan`),
  ADD KEY `fk_verif_pengembalian_barang` (`id_pengembalian_barang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_peminjaman_barang`
--
ALTER TABLE `detail_peminjaman_barang`
  MODIFY `id_detail_peminjaman_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_peminjaman_ruangan`
--
ALTER TABLE `detail_peminjaman_ruangan`
  MODIFY `id_detail_peminjaman_ruangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_pengembalian_barang`
--
ALTER TABLE `detail_pengembalian_barang`
  MODIFY `id_detail_pengembalian_barang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_pengembalian_ruangan`
--
ALTER TABLE `detail_pengembalian_ruangan`
  MODIFY `id_detail_pengembalian_ruangan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fasilitas_ruangan`
--
ALTER TABLE `fasilitas_ruangan`
  MODIFY `id_fasilitas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengembalian_barang`
--
ALTER TABLE `pengembalian_barang`
  MODIFY `id_pengembalian_barang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengembalian_ruangan`
--
ALTER TABLE `pengembalian_ruangan`
  MODIFY `id_pengembalian_ruangan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id_ruangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id_user_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `verifikasi_peminjaman`
--
ALTER TABLE `verifikasi_peminjaman`
  MODIFY `id_verifikasi_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `verifikasi_pengembalian`
--
ALTER TABLE `verifikasi_pengembalian`
  MODIFY `id_verifikasi_pengembalian` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_pic` FOREIGN KEY (`id_pic`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `detail_peminjaman_barang`
--
ALTER TABLE `detail_peminjaman_barang`
  ADD CONSTRAINT `fk_detail_barang_id` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_detail_barang_peminjaman` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE;

--
-- Constraints for table `detail_peminjaman_ruangan`
--
ALTER TABLE `detail_peminjaman_ruangan`
  ADD CONSTRAINT `fk_detail_ruangan_id` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id_ruangan`),
  ADD CONSTRAINT `fk_detail_ruangan_peminjaman` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE;

--
-- Constraints for table `detail_pengembalian_barang`
--
ALTER TABLE `detail_pengembalian_barang`
  ADD CONSTRAINT `fk_detail_pengembalian_barang_header` FOREIGN KEY (`id_pengembalian_barang`) REFERENCES `pengembalian_barang` (`id_pengembalian_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_pengembalian_barang_id` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `detail_pengembalian_ruangan`
--
ALTER TABLE `detail_pengembalian_ruangan`
  ADD CONSTRAINT `fk_detail_pengembalian_ruangan` FOREIGN KEY (`id_pengembalian_ruangan`) REFERENCES `pengembalian_ruangan` (`id_pengembalian_ruangan`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_pengembalian_ruangan_id` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`);

--
-- Constraints for table `fasilitas_ruangan`
--
ALTER TABLE `fasilitas_ruangan`
  ADD CONSTRAINT `fk_fasilitas_ruangan` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_peminjaman_dosen` FOREIGN KEY (`dosen_id`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `pengembalian_barang`
--
ALTER TABLE `pengembalian_barang`
  ADD CONSTRAINT `fk_pengembalian_barang_peminjaman` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id_peminjaman`);

--
-- Constraints for table `pengembalian_ruangan`
--
ALTER TABLE `pengembalian_ruangan`
  ADD CONSTRAINT `fk_pengembalian_ruangan_peminjaman` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id_peminjaman`);

--
-- Constraints for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD CONSTRAINT `fk_ruangan_pic` FOREIGN KEY (`id_pic`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `fk_user_role_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id_role`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_role_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `verifikasi_peminjaman`
--
ALTER TABLE `verifikasi_peminjaman`
  ADD CONSTRAINT `fk_verif_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_verif_verifikator` FOREIGN KEY (`id_verifikator`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `verifikasi_pengembalian`
--
ALTER TABLE `verifikasi_pengembalian`
  ADD CONSTRAINT `fk_verif_pengembalian_barang` FOREIGN KEY (`id_pengembalian_barang`) REFERENCES `pengembalian_barang` (`id_pengembalian_barang`),
  ADD CONSTRAINT `fk_verif_pengembalian_ruangan` FOREIGN KEY (`id_pengembalian_ruangan`) REFERENCES `pengembalian_ruangan` (`id_pengembalian_ruangan`),
  ADD CONSTRAINT `fk_verif_pengembalian_user` FOREIGN KEY (`id_verifikator`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
