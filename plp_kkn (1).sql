-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 29, 2025 at 09:18 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `plp_kkn`
--

-- --------------------------------------------------------

--
-- Table structure for table `akses_modul_user`
--

CREATE TABLE `akses_modul_user` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `id_program` int NOT NULL,
  `aktif` tinyint DEFAULT '1',
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nidn` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `id_prodi` int NOT NULL,
  `fakultas` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `nomor_rekening` varchar(255) DEFAULT NULL,
  `nama_rekening` varchar(255) DEFAULT NULL,
  `buku` varchar(255) DEFAULT NULL,
  `id_sekolah` int DEFAULT NULL,
  `verified_by` int DEFAULT NULL,
  `pesan_gagal` varchar(255) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `status_data` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('dibayar','belum dibayar') DEFAULT 'belum dibayar',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kaprodi`
--

CREATE TABLE `kaprodi` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `id_prodi` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelompok`
--

CREATE TABLE `kelompok` (
  `id` int NOT NULL,
  `id_program` int NOT NULL,
  `nama_kelompok` varchar(255) NOT NULL,
  `id_dosen` int DEFAULT NULL,
  `id_guru` int DEFAULT NULL,
  `id_sekolah` int DEFAULT NULL,
  `id_desa` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kepsek`
--

CREATE TABLE `kepsek` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `id_program` int DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `nomor_rekening` varchar(255) DEFAULT NULL,
  `nama_rekening` varchar(255) DEFAULT NULL,
  `buku` varchar(255) DEFAULT NULL,
  `id_sekolah` int DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `verified_by` int DEFAULT NULL,
  `pesan_gagal` varchar(255) DEFAULT NULL,
  `status_perkawinan` varchar(50) DEFAULT NULL,
  `status_data` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('dibayar','belum dibayar') DEFAULT 'belum dibayar',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nim` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` text,
  `id_prodi` int DEFAULT NULL,
  `id_sekolah` int DEFAULT NULL,
  `id_kelompok` int DEFAULT NULL,
  `ketua` tinyint DEFAULT '0',
  `id_guru` int DEFAULT NULL,
  `id_dosen` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `fakultas` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id`, `nama`, `fakultas`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Pendidikna', 'FT', '2025-11-29 08:59:33', '2025-11-29 08:59:33', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `semester` varchar(10) DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `kode`, `nama`, `tahun_ajaran`, `semester`, `active`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'plp1', 'Program Latihan Profesi I', '2026', 'ganjil', 0, '2025-11-28 03:00:13', NULL, NULL, NULL),
(2, 'plp2', 'Program Latihan Profesi II', '2026', 'genap', 0, '2025-11-28 03:00:13', NULL, NULL, NULL),
(3, 'kkn', 'Kuliah Kerja Nyata', '2026', 'ganjil', 0, '2025-11-28 03:00:13', NULL, NULL, NULL),
(4, '', 'test', '2025', NULL, 0, '2025-11-29 08:27:41', NULL, NULL, NULL),
(5, '', 'asdasdasd', '2026', NULL, 0, '2025-11-29 08:31:11', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sekolah`
--

CREATE TABLE `sekolah` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sekolah`
--

INSERT INTO `sekolah` (`id`, `nama`, `alamat`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'SMKN 1 Medan', 'JLN SK', '2025-11-29 08:48:50', '2025-11-29 08:48:50', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','guru','dosen','kaprodi','admin','super_admin') NOT NULL,
  `fakultas` varchar(255) DEFAULT NULL,
  `has_change` tinyint DEFAULT '0',
  `id_program` int DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `role`, `fakultas`, `has_change`, `id_program`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'ronisinaga@unimed.ac.id', 'Roni Sinaga', '$2y$10$3LLhC.iwrRWGiA0MXGHiLengfI.7Ku.cyOKrOmVJYSKgy1sIKOW6a', 'super_admin', NULL, 1, NULL, NULL, NULL, '2025-11-28 03:42:26', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akses_modul_user`
--
ALTER TABLE `akses_modul_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_akses_user` (`id_user`),
  ADD KEY `fk_akses_program` (`id_program`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nidn` (`nidn`),
  ADD KEY `fk_dosen_user` (`id_user`),
  ADD KEY `fk_dosen_prodi` (`id_prodi`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_guru_user` (`id_user`);

--
-- Indexes for table `kaprodi`
--
ALTER TABLE `kaprodi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kaprodi_user` (`id_user`),
  ADD KEY `fk_kaprodi_prodi` (`id_prodi`);

--
-- Indexes for table `kelompok`
--
ALTER TABLE `kelompok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kelompok_program` (`id_program`),
  ADD KEY `fk_kelompok_dosen` (`id_dosen`),
  ADD KEY `fk_kelompok_guru` (`id_guru`),
  ADD KEY `fk_kelompok_sekolah` (`id_sekolah`);

--
-- Indexes for table `kepsek`
--
ALTER TABLE `kepsek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kepsek_user` (`id_user`),
  ADD KEY `fk_kepsek_program` (`id_program`),
  ADD KEY `fk_kepsek_sekolah` (`id_sekolah`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `fk_mahasiswa_user` (`id_user`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sekolah`
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akses_modul_user`
--
ALTER TABLE `akses_modul_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kaprodi`
--
ALTER TABLE `kaprodi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelompok`
--
ALTER TABLE `kelompok`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kepsek`
--
ALTER TABLE `kepsek`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sekolah`
--
ALTER TABLE `sekolah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `akses_modul_user`
--
ALTER TABLE `akses_modul_user`
  ADD CONSTRAINT `fk_akses_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_akses_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `fk_dosen_prodi` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dosen_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `fk_guru_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kaprodi`
--
ALTER TABLE `kaprodi`
  ADD CONSTRAINT `fk_kaprodi_prodi` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kaprodi_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelompok`
--
ALTER TABLE `kelompok`
  ADD CONSTRAINT `fk_kelompok_dosen` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelompok_guru` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelompok_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelompok_sekolah` FOREIGN KEY (`id_sekolah`) REFERENCES `sekolah` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `kepsek`
--
ALTER TABLE `kepsek`
  ADD CONSTRAINT `fk_kepsek_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kepsek_sekolah` FOREIGN KEY (`id_sekolah`) REFERENCES `sekolah` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kepsek_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `fk_mahasiswa_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
