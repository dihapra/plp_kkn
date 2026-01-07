-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 02, 2026 at 02:23 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.28

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
  `id_user` INT NOT NULL,

  `nama` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `no_hp` VARCHAR(50) DEFAULT NULL,
  `nik` VARCHAR(20) DEFAULT NULL,

  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `created_by` INT DEFAULT NULL,
  `updated_by` INT DEFAULT NULL
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `kepsek`
--

CREATE TABLE `kepsek` (
  `id` int NOT NULL,
  `id_user` INT NOT NULL,

  `nama` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `no_hp` VARCHAR(50) DEFAULT NULL,
  `nik` VARCHAR(20) DEFAULT NULL,


  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `created_by` INT DEFAULT NULL,
  `updated_by` INT DEFAULT NULL
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

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
  `agama` varchar(255) DEFAULT NULL,
  `id_prodi` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa_true`
--

CREATE TABLE `mahasiswa_true` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nim` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` text,
  `id_prodi` int DEFAULT NULL,
  `id_program` int DEFAULT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `program_dosen`
--

CREATE TABLE `program_dosen` (
  `id` int NOT NULL,
  `id_program` int NOT NULL,
  `id_dosen` int NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_guru`
--

CREATE TABLE `program_guru` (
  `id` int NOT NULL,

  `id_program` INT NOT NULL,
  `id_guru` INT NOT NULL,

  -- penempatan (program scoped)
  `id_program_sekolah` INT DEFAULT NULL,

  -- status & verifikasi
  `status_data` VARCHAR(50) DEFAULT NULL,
  `verified_by` INT DEFAULT NULL,
  `verified_at` DATETIME DEFAULT NULL,
  `pesan_gagal` VARCHAR(255) DEFAULT NULL,

  -- pembayaran (PER PROGRAM)
  `status_pembayaran` ENUM('dibayar','belum dibayar') DEFAULT 'belum dibayar',
  `paid_at` DATETIME DEFAULT NULL,
  `status_perkawinan` VARCHAR(50) DEFAULT NULL,
  -- snapshot rekening (ANTI DATA BERUBAH)
  `bank_snapshot` VARCHAR(255) DEFAULT NULL,
  `nomor_rekening_snapshot` VARCHAR(255) DEFAULT NULL,
  `nama_rekening_snapshot` VARCHAR(255) DEFAULT NULL,

  -- dokumen per program
  `buku` VARCHAR(255) DEFAULT NULL,
  `foto_ktp` VARCHAR(255) DEFAULT NULL,

  -- histori keikutsertaan
  `valid_from` DATETIME DEFAULT NULL,
  `valid_to` DATETIME DEFAULT NULL,

  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `created_by` INT DEFAULT NULL,
  `updated_by` INT DEFAULT NULL
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_kelompok`
--

CREATE TABLE `program_kelompok` (
  `id` int NOT NULL,
  `id_program` int NOT NULL,
  `nama_kelompok` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_program_dosen` int DEFAULT NULL,
  `id_program_guru` int DEFAULT NULL,
  `id_program_sekolah` int DEFAULT NULL,
  `id_program_desa` int DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_kelompok_anggota`
--

CREATE TABLE `program_kelompok_anggota` (
  `id` int NOT NULL,
  `id_program_kelompok` int NOT NULL,
  `id_program_mahasiswa` int NOT NULL,
  `peran` enum('ketua','anggota') COLLATE utf8mb4_general_ci DEFAULT 'anggota',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_kepsek`
--
CREATE TABLE `program_kepsek` (
  `id` int NOT NULL,

  `id_program` INT NOT NULL,
  `id_kepsek` INT NOT NULL,

  -- penempatan (program scoped)
  `id_program_sekolah` INT DEFAULT NULL,

  -- status & verifikasi
  `status_data` VARCHAR(50) DEFAULT NULL,
  `verified_by` INT DEFAULT NULL,
  `verified_at` DATETIME DEFAULT NULL,
  `pesan_gagal` VARCHAR(255) DEFAULT NULL,

  -- pembayaran (PER PROGRAM)
  `status_pembayaran` ENUM('dibayar','belum dibayar') DEFAULT 'belum dibayar',
  `paid_at` DATETIME DEFAULT NULL,
  `status_perkawinan` VARCHAR(50) DEFAULT NULL,
  -- snapshot rekening (ANTI DATA BERUBAH)
  `bank_snapshot` VARCHAR(255) DEFAULT NULL,
  `nomor_rekening_snapshot` VARCHAR(255) DEFAULT NULL,
  `nama_rekening_snapshot` VARCHAR(255) DEFAULT NULL,

  -- dokumen per program
  `buku` VARCHAR(255) DEFAULT NULL,
  `foto_ktp` VARCHAR(255) DEFAULT NULL,

  -- histori keikutsertaan
  `valid_from` DATETIME DEFAULT NULL,
  `valid_to` DATETIME DEFAULT NULL,

  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `created_by` INT DEFAULT NULL,
  `updated_by` INT DEFAULT NULL
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_mahasiswa`
--

CREATE TABLE `program_mahasiswa` (
  `id` int NOT NULL,
  `id_program` int NOT NULL,
  `id_mahasiswa` int NOT NULL,
  `id_sekolah` int DEFAULT NULL,
  `id_kelompok` int DEFAULT NULL,
  `id_guru` int DEFAULT NULL,
  `id_dosen` int DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_by` int DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_sekolah`
--

CREATE TABLE `program_sekolah` (
  `id` int NOT NULL,
  `id_program` int NOT NULL,
  `id_sekolah` int NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `syarat_mapel`
--

CREATE TABLE `syarat_mapel` (
  `id` int UNSIGNED NOT NULL,
  `id_program_mahasiswa` int NOT NULL,
  `total_sks` int DEFAULT NULL,
  `filsafat_pendidikan` enum('Lulus','Proses','Belum Lulus') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Belum Lulus',
  `profesi_kependidikan` enum('Lulus','Proses','Belum Lulus') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Belum Lulus',
  `perkembangan_peserta_didik` enum('Lulus','Proses','Belum Lulus') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Belum Lulus',
  `psikologi_pendidikan` enum('Lulus','Proses','Belum Lulus') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Belum Lulus',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','guru','dosen','kaprodi','admin','super_admin','kepsek') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fakultas` varchar(255) DEFAULT NULL,
  `has_change` tinyint DEFAULT '0',
  `id_program` int DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  ADD UNIQUE KEY `uq_guru_user` (`id_user`);

--
-- Indexes for table `kaprodi`
--
ALTER TABLE `kaprodi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kaprodi_user` (`id_user`),
  ADD KEY `fk_kaprodi_prodi` (`id_prodi`);

--
-- Indexes for table `kepsek`
--
ALTER TABLE `kepsek`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_kepsek_user` (`id_user`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `fk_mahasiswa_user` (`id_user`);

--
-- Indexes for table `mahasiswa_true`
--
ALTER TABLE `mahasiswa_true`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`);

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
-- Indexes for table `program_dosen`
--
ALTER TABLE `program_dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_program_dosen_version` (`id_program`,`id_dosen`,`valid_from`),
  ADD KEY `idx_program_dosen_program` (`id_program`),
  ADD KEY `idx_program_dosen_dosen` (`id_dosen`);

--
-- Indexes for table `program_guru`
--
ALTER TABLE `program_guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_program_guru_active` (`id_program`,`id_guru`,`valid_from`),
  ADD KEY `idx_program_guru_program` (`id_program`),
  ADD KEY `idx_program_guru_guru` (`id_guru`),
  ADD KEY `idx_program_guru_program_sekolah` (`id_program_sekolah`);

--
-- Indexes for table `program_kelompok`
--
ALTER TABLE `program_kelompok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_program_kelompok` (`id_program`,`nama_kelompok`),
  ADD KEY `id_program_dosen` (`id_program_dosen`),
  ADD KEY `id_program_guru` (`id_program_guru`),
  ADD KEY `id_program_sekolah` (`id_program_sekolah`);

--
-- Indexes for table `program_kelompok_anggota`
--
ALTER TABLE `program_kelompok_anggota`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_kelompok_anggota` (`id_program_kelompok`,`id_program_mahasiswa`),
  ADD KEY `id_program_mahasiswa` (`id_program_mahasiswa`);

--
-- Indexes for table `program_kepsek`
--
ALTER TABLE `program_kepsek`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_program_kepsek_active` (`id_program`,`id_kepsek`,`valid_from`),
  ADD KEY `idx_program_kepsek_program` (`id_program`),
  ADD KEY `idx_program_kepsek_kepsek` (`id_kepsek`),
  ADD KEY `idx_program_kepsek_program_sekolah` (`id_program_sekolah`);

--
-- Indexes for table `program_mahasiswa`
--
ALTER TABLE `program_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_program_mahasiswa_version` (`id_program`,`id_mahasiswa`,`valid_from`),
  ADD KEY `idx_program_mahasiswa_program` (`id_program`),
  ADD KEY `idx_program_mahasiswa_mahasiswa` (`id_mahasiswa`);

--
-- Indexes for table `program_sekolah`
--
ALTER TABLE `program_sekolah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_program_sekolah_version` (`id_program`,`id_sekolah`,`valid_from`),
  ADD KEY `idx_program_sekolah_program` (`id_program`),
  ADD KEY `idx_program_sekolah_sekolah` (`id_sekolah`);

--
-- Indexes for table `sekolah`
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `syarat_mapel`
--
ALTER TABLE `syarat_mapel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_syarat_mapel_program_mahasiswa` (`id_program_mahasiswa`),
  ADD KEY `idx_syarat_mapel_program_mahasiswa` (`id_program_mahasiswa`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT for table `mahasiswa_true`
--
ALTER TABLE `mahasiswa_true`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_dosen`
--
ALTER TABLE `program_dosen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_guru`
--
ALTER TABLE `program_guru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_kelompok`
--
ALTER TABLE `program_kelompok`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_kelompok_anggota`
--
ALTER TABLE `program_kelompok_anggota`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_kepsek`
--
ALTER TABLE `program_kepsek`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_mahasiswa`
--
ALTER TABLE `program_mahasiswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_sekolah`
--
ALTER TABLE `program_sekolah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sekolah`
--
ALTER TABLE `sekolah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `syarat_mapel`
--
ALTER TABLE `syarat_mapel`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `fk_guru_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `kaprodi`
--
ALTER TABLE `kaprodi`
  ADD CONSTRAINT `fk_kaprodi_prodi` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kaprodi_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kepsek`
--
ALTER TABLE `kepsek`
  ADD CONSTRAINT `fk_kepsek_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `fk_mahasiswa_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `program_dosen`
--
ALTER TABLE `program_dosen`
  ADD CONSTRAINT `fk_program_dosen_dosen` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_program_dosen_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `program_guru`
--
ALTER TABLE `program_guru`
  ADD CONSTRAINT `fk_program_guru_guru` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_program_guru_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_program_guru_sekolah` FOREIGN KEY (`id_program_sekolah`) REFERENCES `program_sekolah` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `program_kelompok`
--
ALTER TABLE `program_kelompok`
  ADD CONSTRAINT `program_kelompok_ibfk_1` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`),
  ADD CONSTRAINT `program_kelompok_ibfk_2` FOREIGN KEY (`id_program_dosen`) REFERENCES `program_dosen` (`id`),
  ADD CONSTRAINT `program_kelompok_ibfk_3` FOREIGN KEY (`id_program_guru`) REFERENCES `program_guru` (`id`),
  ADD CONSTRAINT `program_kelompok_ibfk_4` FOREIGN KEY (`id_program_sekolah`) REFERENCES `program_sekolah` (`id`);

--
-- Constraints for table `program_kelompok_anggota`
--
ALTER TABLE `program_kelompok_anggota`
  ADD CONSTRAINT `program_kelompok_anggota_ibfk_1` FOREIGN KEY (`id_program_kelompok`) REFERENCES `program_kelompok` (`id`),
  ADD CONSTRAINT `program_kelompok_anggota_ibfk_2` FOREIGN KEY (`id_program_mahasiswa`) REFERENCES `program_mahasiswa` (`id`);

--
-- Constraints for table `program_kepsek`
--
ALTER TABLE `program_kepsek`
  ADD CONSTRAINT `fk_program_kepsek_kepsek` FOREIGN KEY (`id_kepsek`) REFERENCES `kepsek` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_program_kepsek_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_program_kepsek_sekolah` FOREIGN KEY (`id_program_sekolah`) REFERENCES `program_sekolah` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `program_mahasiswa`
--
ALTER TABLE `program_mahasiswa`
  ADD CONSTRAINT `fk_program_mahasiswa_mahasiswa` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_program_mahasiswa_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `program_sekolah`
--
ALTER TABLE `program_sekolah`
  ADD CONSTRAINT `fk_program_sekolah_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_program_sekolah_sekolah` FOREIGN KEY (`id_sekolah`) REFERENCES `sekolah` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `syarat_mapel`
--
ALTER TABLE `syarat_mapel`
  ADD CONSTRAINT `fk_syarat_mapel_program_mahasiswa` FOREIGN KEY (`id_program_mahasiswa`) REFERENCES `program_mahasiswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
