-- --------------------------------------------------------
-- Table structure for table `syarat_mapel`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `syarat_mapel` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_program` int NOT NULL,
  `id_mahasiswa` int NOT NULL,
  `filsafat_pendidikan` enum('Lulus','Proses','Belum Lulus') NOT NULL DEFAULT 'Belum Lulus',
  `profesi_kependidikan` enum('Lulus','Proses','Belum Lulus') NOT NULL DEFAULT 'Belum Lulus',
  `perkembangan_peserta_didik` enum('Lulus','Proses','Belum Lulus') NOT NULL DEFAULT 'Belum Lulus',
  `psikologi_pendidikan` enum('Lulus','Proses','Belum Lulus') NOT NULL DEFAULT 'Belum Lulus',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `syarat_mapel_program_idx` (`id_program`),
  KEY `syarat_mapel_mahasiswa_idx` (`id_mahasiswa`),
  CONSTRAINT `fk_syarat_mapel_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_syarat_mapel_mahasiswa` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
