-- =====================================================================
-- Database Schema & Initial Data for Perpustakaan MNC University
-- Target Database: MySQL / MariaDB (Laragon / Standalone)
-- Generated for MNCULIB Project
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `perpustakaan_mncu`
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `perpustakaan_mncu`;

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- 1. Table: peran
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `peran`;
CREATE TABLE `peran` (
  `id_peran` varchar(10) NOT NULL COMMENT 'Format: RL-001',
  `uuid` varchar(36) NOT NULL,
  `nama_peran` varchar(30) NOT NULL,
  `deskripsi_peran` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_peran`),
  UNIQUE KEY `peran_uuid_unique` (`uuid`),
  UNIQUE KEY `peran_nama_peran_unique` (`nama_peran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. Table: prodi
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `prodi`;
CREATE TABLE `prodi` (
  `id_prodi` varchar(10) NOT NULL COMMENT 'Format: PR-001',
  `uuid` varchar(36) NOT NULL,
  `nama_prodi` varchar(100) NOT NULL,
  `jenjang` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_prodi`),
  UNIQUE KEY `prodi_uuid_unique` (`uuid`),
  UNIQUE KEY `prodi_nama_prodi_unique` (`nama_prodi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. Table: pengguna
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE `pengguna` (
  `id_pengguna` varchar(10) NOT NULL COMMENT 'Format: PG-001',
  `uuid` varchar(36) NOT NULL,
  `id_peran` varchar(10) NOT NULL,
  `id_prodi` varchar(10) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  `nomor_identitas` varchar(30) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `pengguna_uuid_unique` (`uuid`),
  UNIQUE KEY `pengguna_email_unique` (`email`),
  UNIQUE KEY `pengguna_nomor_identitas_unique` (`nomor_identitas`),
  KEY `pengguna_id_peran_foreign` (`id_peran`),
  KEY `pengguna_id_prodi_foreign` (`id_prodi`),
  CONSTRAINT `pengguna_id_peran_foreign` FOREIGN KEY (`id_peran`) REFERENCES `peran` (`id_peran`) ON UPDATE CASCADE,
  CONSTRAINT `pengguna_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 4. Table: profil_pustakawan
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `profil_pustakawan`;
CREATE TABLE `profil_pustakawan` (
  `id_profil_pustakawan` varchar(10) NOT NULL COMMENT 'Format: PP-001',
  `uuid` varchar(36) NOT NULL,
  `id_pengguna` varchar(10) NOT NULL,
  `nama_pustakawan` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `foto_pustakawan` varchar(255) DEFAULT NULL,
  `deskripsi_pustakawan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_profil_pustakawan`),
  UNIQUE KEY `profil_pustakawan_uuid_unique` (`uuid`),
  UNIQUE KEY `profil_pustakawan_id_pengguna_unique` (`id_pengguna`),
  CONSTRAINT `profil_pustakawan_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 5. Table: jenis_buku
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `jenis_buku`;
CREATE TABLE `jenis_buku` (
  `id_jenis_buku` varchar(10) NOT NULL COMMENT 'Format: JB-001',
  `uuid` varchar(36) NOT NULL,
  `nama_jenis_buku` varchar(100) NOT NULL,
  `deskripsi_jenis` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_jenis_buku`),
  UNIQUE KEY `jenis_buku_uuid_unique` (`uuid`),
  UNIQUE KEY `jenis_buku_nama_jenis_buku_unique` (`nama_jenis_buku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 6. Table: kategori_buku
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `kategori_buku`;
CREATE TABLE `kategori_buku` (
  `id_kategori_buku` varchar(10) NOT NULL COMMENT 'Format: KB-001',
  `uuid` varchar(36) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi_kategori` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kategori_buku`),
  UNIQUE KEY `kategori_buku_uuid_unique` (`uuid`),
  UNIQUE KEY `kategori_buku_nama_kategori_unique` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 7. Table: subjek_buku
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `subjek_buku`;
CREATE TABLE `subjek_buku` (
  `id_subjek_buku` varchar(10) NOT NULL COMMENT 'Format: SB-001',
  `uuid` varchar(36) NOT NULL,
  `nama_subjek` varchar(100) NOT NULL,
  `deskripsi_subjek` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_subjek_buku`),
  UNIQUE KEY `subjek_buku_uuid_unique` (`uuid`),
  UNIQUE KEY `subjek_buku_nama_subjek_unique` (`nama_subjek`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 8. Table: penulis
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `penulis`;
CREATE TABLE `penulis` (
  `id_penulis` varchar(10) NOT NULL COMMENT 'Format: PGN-001',
  `uuid` varchar(36) NOT NULL,
  `nama_penulis` varchar(100) NOT NULL,
  `deskripsi_penulis` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_penulis`),
  UNIQUE KEY `penulis_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 9. Table: penerbit
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `penerbit`;
CREATE TABLE `penerbit` (
  `id_penerbit` varchar(10) NOT NULL COMMENT 'Format: PN-001',
  `uuid` varchar(36) NOT NULL,
  `nama_penerbit` varchar(100) NOT NULL,
  `deskripsi_penerbit` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_penerbit`),
  UNIQUE KEY `penerbit_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 10. Table: buku
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `buku`;
CREATE TABLE `buku` (
  `id_buku` varchar(10) NOT NULL COMMENT 'Format: BK-001',
  `uuid` varchar(36) NOT NULL,
  `id_jenis_buku` varchar(10) NOT NULL,
  `id_kategori_buku` varchar(10) NOT NULL,
  `id_penerbit` varchar(10) DEFAULT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `tahun_terbit` int DEFAULT NULL,
  `edisi` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `sampul_buku` varchar(255) DEFAULT NULL,
  `jumlah_halaman` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_buku`),
  UNIQUE KEY `buku_uuid_unique` (`uuid`),
  KEY `buku_id_jenis_buku_foreign` (`id_jenis_buku`),
  KEY `buku_id_kategori_buku_foreign` (`id_kategori_buku`),
  KEY `buku_id_penerbit_foreign` (`id_penerbit`),
  CONSTRAINT `buku_id_jenis_buku_foreign` FOREIGN KEY (`id_jenis_buku`) REFERENCES `jenis_buku` (`id_jenis_buku`) ON UPDATE CASCADE,
  CONSTRAINT `buku_id_kategori_buku_foreign` FOREIGN KEY (`id_kategori_buku`) REFERENCES `kategori_buku` (`id_kategori_buku`) ON UPDATE CASCADE,
  CONSTRAINT `buku_id_penerbit_foreign` FOREIGN KEY (`id_penerbit`) REFERENCES `penerbit` (`id_penerbit`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 11. Table: buku_penulis
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `buku_penulis`;
CREATE TABLE `buku_penulis` (
  `id_buku_penulis` varchar(10) NOT NULL COMMENT 'Format: BP-001',
  `uuid` varchar(36) NOT NULL,
  `id_buku` varchar(10) NOT NULL,
  `id_penulis` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_buku_penulis`),
  UNIQUE KEY `buku_penulis_uuid_unique` (`uuid`),
  UNIQUE KEY `buku_penulis_buku_penulis_unique` (`id_buku`, `id_penulis`),
  KEY `buku_penulis_id_penulis_foreign` (`id_penulis`),
  CONSTRAINT `buku_penulis_id_buku_foreign` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `buku_penulis_id_penulis_foreign` FOREIGN KEY (`id_penulis`) REFERENCES `penulis` (`id_penulis`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 12. Table: buku_subjek
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `buku_subjek`;
CREATE TABLE `buku_subjek` (
  `id_buku_subjek` varchar(10) NOT NULL COMMENT 'Format: BS-001',
  `uuid` varchar(36) NOT NULL,
  `id_buku` varchar(10) NOT NULL,
  `id_subjek_buku` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_buku_subjek`),
  UNIQUE KEY `buku_subjek_uuid_unique` (`uuid`),
  UNIQUE KEY `buku_subjek_buku_subjek_unique` (`id_buku`, `id_subjek_buku`),
  KEY `buku_subjek_id_subjek_buku_foreign` (`id_subjek_buku`),
  CONSTRAINT `buku_subjek_id_buku_foreign` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `buku_subjek_id_subjek_buku_foreign` FOREIGN KEY (`id_subjek_buku`) REFERENCES `subjek_buku` (`id_subjek_buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 13. Table: eksemplar_buku
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `eksemplar_buku`;
CREATE TABLE `eksemplar_buku` (
  `id_eksemplar` varchar(10) NOT NULL COMMENT 'Format: EK-001',
  `uuid` varchar(36) NOT NULL,
  `id_buku` varchar(10) NOT NULL,
  `kode_inventaris` varchar(50) NOT NULL,
  `nomor_panggil` varchar(50) DEFAULT NULL,
  `lokasi_rak` varchar(100) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Tersedia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_eksemplar`),
  UNIQUE KEY `eksemplar_buku_uuid_unique` (`uuid`),
  UNIQUE KEY `eksemplar_buku_kode_inventaris_unique` (`kode_inventaris`),
  KEY `eksemplar_buku_id_buku_foreign` (`id_buku`),
  CONSTRAINT `eksemplar_buku_id_buku_foreign` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 14. Table: kunjungan_buku
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `kunjungan_buku`;
CREATE TABLE `kunjungan_buku` (
  `id_kunjungan_buku` varchar(10) NOT NULL COMMENT 'Format: KJB-001',
  `uuid` varchar(36) NOT NULL,
  `id_buku` varchar(10) NOT NULL,
  `jumlah_kunjungan` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kunjungan_buku`),
  UNIQUE KEY `kunjungan_buku_uuid_unique` (`uuid`),
  UNIQUE KEY `kunjungan_buku_id_buku_unique` (`id_buku`),
  CONSTRAINT `kunjungan_buku_id_buku_foreign` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 15. Table: berita
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `berita`;
CREATE TABLE `berita` (
  `id_berita` varchar(10) NOT NULL COMMENT 'Format: BR-001',
  `uuid` varchar(36) NOT NULL,
  `id_pengguna` varchar(10) NOT NULL,
  `judul_berita` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `kategori_berita` varchar(100) NOT NULL DEFAULT 'Pengumuman',
  `deskripsi_berita` varchar(255) NOT NULL,
  `isi_berita` longtext NOT NULL,
  `gambar_berita` varchar(255) DEFAULT NULL,
  `tanggal_publikasi` date DEFAULT NULL,
  `views_count` bigint unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_berita`),
  UNIQUE KEY `berita_uuid_unique` (`uuid`),
  UNIQUE KEY `berita_slug_unique` (`slug`),
  KEY `berita_id_pengguna_foreign` (`id_pengguna`),
  CONSTRAINT `berita_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 16. Table: acara_perpustakaan
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `acara_perpustakaan`;
CREATE TABLE `acara_perpustakaan` (
  `id_acara` varchar(10) NOT NULL COMMENT 'Format: AC-001',
  `uuid` varchar(36) NOT NULL,
  `id_pengguna` varchar(10) NOT NULL,
  `nama_acara` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `kategori_acara` varchar(100) NOT NULL DEFAULT 'Acara Perpustakaan',
  `ringkasan_acara` varchar(255) DEFAULT NULL,
  `deskripsi_acara` text DEFAULT NULL,
  `gambar_acara` varchar(255) DEFAULT NULL,
  `narasumber` varchar(100) DEFAULT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `lokasi_acara` varchar(255) DEFAULT NULL,
  `tautan_pendaftaran` varchar(255) DEFAULT NULL,
  `kuota` int unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_acara`),
  UNIQUE KEY `acara_perpustakaan_uuid_unique` (`uuid`),
  UNIQUE KEY `acara_perpustakaan_slug_unique` (`slug`),
  KEY `acara_perpustakaan_id_pengguna_foreign` (`id_pengguna`),
  CONSTRAINT `acara_perpustakaan_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 17. Table: kontak
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `kontak`;
CREATE TABLE `kontak` (
  `id_kontak` varchar(10) NOT NULL COMMENT 'Format: KK-001',
  `uuid` varchar(36) NOT NULL,
  `id_pengguna` varchar(10) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `email_kontak` varchar(100) NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `isi_pesan` text NOT NULL,
  `waktu_kirim` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kontak`),
  UNIQUE KEY `kontak_uuid_unique` (`uuid`),
  KEY `kontak_id_pengguna_foreign` (`id_pengguna`),
  CONSTRAINT `kontak_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 18. Table: log_kunjungan
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `log_kunjungan`;
CREATE TABLE `log_kunjungan` (
  `id_log_kunjungan` varchar(10) NOT NULL COMMENT 'Format: LK-001',
  `uuid` varchar(36) NOT NULL,
  `id_pengguna` varchar(10) DEFAULT NULL,
  `waktu_kunjungan` datetime NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_log_kunjungan`),
  UNIQUE KEY `log_kunjungan_uuid_unique` (`uuid`),
  KEY `log_kunjungan_session_id_index` (`session_id`),
  KEY `log_kunjungan_id_pengguna_foreign` (`id_pengguna`),
  CONSTRAINT `log_kunjungan_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Initial Data Seed
-- ---------------------------------------------------------------------
INSERT INTO `peran` (`id_peran`, `uuid`, `nama_peran`, `deskripsi_peran`, `created_at`, `updated_at`) VALUES
('RL-001', 'e0f76921-72f1-4df2-a3c3-61a7a10a1001', 'Kepala Perpustakaan', 'Pimpinan unit perpustakaan MNC University', NOW(), NOW()),
('RL-002', 'e0f76921-72f1-4df2-a3c3-61a7a10a1002', 'Pustakawan', 'Staf pelayanan sirkulasi dan literasi informasi', NOW(), NOW()),
('RL-003', 'e0f76921-72f1-4df2-a3c3-61a7a10a1003', 'Mahasiswa', 'Pemustaka mahasiswa MNC University', NOW(), NOW()),
('RL-004', 'e0f76921-72f1-4df2-a3c3-61a7a10a1004', 'Dosen', 'Tenaga pendidik dan periset MNC University', NOW(), NOW());

INSERT INTO `prodi` (`id_prodi`, `uuid`, `nama_prodi`, `jenjang`, `created_at`, `updated_at`) VALUES
('PR-001', 'e0f76921-72f1-4df2-a3c3-61a7a10a2001', 'Sains Data', 'S1', NOW(), NOW()),
('PR-002', 'e0f76921-72f1-4df2-a3c3-61a7a10a2002', 'Informatika', 'S1', NOW(), NOW()),
('PR-003', 'e0f76921-72f1-4df2-a3c3-61a7a10a2003', 'Sistem Informasi', 'S1', NOW(), NOW()),
('PR-004', 'e0f76921-72f1-4df2-a3c3-61a7a10a2004', 'Desain Komunikasi Visual', 'S1', NOW(), NOW()),
('PR-005', 'e0f76921-72f1-4df2-a3c3-61a7a10a2005', 'Manajemen', 'S1', NOW(), NOW()),
('PR-006', 'e0f76921-72f1-4df2-a3c3-61a7a10a2006', 'Akuntansi', 'S1', NOW(), NOW()),
('PR-007', 'e0f76921-72f1-4df2-a3c3-61a7a10a2007', 'Pendidikan Bahasa Inggris', 'S1', NOW(), NOW());

INSERT INTO `pengguna` (`id_pengguna`, `uuid`, `id_peran`, `id_prodi`, `nama_lengkap`, `email`, `kata_sandi`, `nomor_identitas`, `created_at`, `updated_at`) VALUES
('PG-001', 'e0f76921-72f1-4df2-a3c3-61a7a10a3001', 'RL-001', NULL, 'Dr. H. Muhammad Arifin, M.Hum.', 'arifin@mncu.ac.id', '$2y$12$N30G1R4w3Gj.V3Mv9R.jXer.fBqXj11gU96kZeqJ2Q3j008N.kM4i', 'PST-MNCU-001', NOW(), NOW()),
('PG-002', 'e0f76921-72f1-4df2-a3c3-61a7a10a3002', 'RL-002', NULL, 'Sarah Amalia, S.I.Pust.', 'sarah.amalia@mncu.ac.id', '$2y$12$N30G1R4w3Gj.V3Mv9R.jXer.fBqXj11gU96kZeqJ2Q3j008N.kM4i', 'PST-MNCU-002', NOW(), NOW()),
('PG-003', 'e0f76921-72f1-4df2-a3c3-61a7a10a3003', 'RL-002', NULL, 'Fajar Pratama, S.Kom.', 'fajar.pratama@mncu.ac.id', '$2y$12$N30G1R4w3Gj.V3Mv9R.jXer.fBqXj11gU96kZeqJ2Q3j008N.kM4i', 'PST-MNCU-003', NOW(), NOW()),
('PG-004', 'e0f76921-72f1-4df2-a3c3-61a7a10a3004', 'RL-003', 'PR-002', 'Ahmad Fauzi Rahman', 'fauzi.mhs@mncu.ac.id', '$2y$12$N30G1R4w3Gj.V3Mv9R.jXer.fBqXj11gU96kZeqJ2Q3j008N.kM4i', '2024010012', NOW(), NOW()),
('PG-005', 'e0f76921-72f1-4df2-a3c3-61a7a10a3005', 'RL-004', 'PR-005', 'Prof. Dr. Hendra Gunawan, S.E., M.M.', 'hendra.gunawan@mncu.ac.id', '$2y$12$N30G1R4w3Gj.V3Mv9R.jXer.fBqXj11gU96kZeqJ2Q3j008N.kM4i', 'NIDN-0312048501', NOW(), NOW());

INSERT INTO `profil_pustakawan` (`id_profil_pustakawan`, `uuid`, `id_pengguna`, `nama_pustakawan`, `jabatan`, `foto_pustakawan`, `deskripsi_pustakawan`, `created_at`, `updated_at`) VALUES
('PP-001', 'e0f76921-72f1-4df2-a3c3-61a7a10a4001', 'PG-001', 'Dr. H. Muhammad Arifin, M.Hum.', 'Kepala Perpustakaan MNC University', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80', 'Pengelolaan layanan strategis, pengembangan koleksi perpustakaan, dan literasi informasi.', NOW(), NOW()),
('PP-002', 'e0f76921-72f1-4df2-a3c3-61a7a10a4002', 'PG-002', 'Sarah Amalia, S.I.Pust.', 'Pustakawan Layanan Sirkulasi & Referensi', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80', 'Layanan sirkulasi, bimbingan penelusuran referensi, dan verifikasi bebas pustaka.', NOW(), NOW()),
('PP-003', 'e0f76921-72f1-4df2-a3c3-61a7a10a4003', 'PG-003', 'Fajar Pratama, S.Kom.', 'Pustakawan Teknologi Informasi & E-Resources', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80', 'Pengelolaan sistem automasi katalog OPAC, repository institusi, dan portal jurnal ilmiah.', NOW(), NOW());

INSERT INTO `jenis_buku` (`id_jenis_buku`, `uuid`, `nama_jenis_buku`, `deskripsi_jenis`, `created_at`, `updated_at`) VALUES
('JB-001', 'e0f76921-72f1-4df2-a3c3-61a7a10a5001', 'Buku Teks', 'Buku pegangan materi kuliah utama', NOW(), NOW()),
('JB-002', 'e0f76921-72f1-4df2-a3c3-61a7a10a5002', 'Buku Referensi', 'Ensiklopedia, kamus, dan buku acuan', NOW(), NOW()),
('JB-003', 'e0f76921-72f1-4df2-a3c3-61a7a10a5003', 'Karya Ilmiah / Skripsi', 'Laporan tugas akhir dan skripsi mahasiswa', NOW(), NOW()),
('JB-004', 'e0f76921-72f1-4df2-a3c3-61a7a10a5004', 'Jurnal Ilmiah', 'Publikasi artikel penelitian berkala', NOW(), NOW()),
('JB-005', 'e0f76921-72f1-4df2-a3c3-61a7a10a5005', 'Buku Fiksi & Sastra', 'Novel dan karya sastra umum', NOW(), NOW());

INSERT INTO `kategori_buku` (`id_kategori_buku`, `uuid`, `nama_kategori`, `deskripsi_kategori`, `created_at`, `updated_at`) VALUES
('KB-001', 'e0f76921-72f1-4df2-a3c3-61a7a10a6001', 'Teknologi & Ilmu Komputer', 'Pemrograman, software architecture, jaringan', NOW(), NOW()),
('KB-002', 'e0f76921-72f1-4df2-a3c3-61a7a10a6002', 'Bisnis & Manajemen', 'Marketing, manajemen strategi, kewirausahaan', NOW(), NOW()),
('KB-003', 'e0f76921-72f1-4df2-a3c3-61a7a10a6003', 'Desain Visual & Komunikasi', 'DKV, tipografi, seni grafis', NOW(), NOW()),
('KB-004', 'e0f76921-72f1-4df2-a3c3-61a7a10a6004', 'Sains Data & Matematika', 'Statistika, analitika data, kalkulus', NOW(), NOW()),
('KB-005', 'e0f76921-72f1-4df2-a3c3-61a7a10a6005', 'Pengembangan Diri & Sastra', 'Self improvement, sastra Indonesia', NOW(), NOW());

INSERT INTO `subjek_buku` (`id_subjek_buku`, `uuid`, `nama_subjek`, `deskripsi_subjek`, `created_at`, `updated_at`) VALUES
('SB-001', 'e0f76921-72f1-4df2-a3c3-61a7a10a7001', 'Algoritma & Struktur Data', 'Struktur data linear dan non-linear serta kompleksitas', NOW(), NOW()),
('SB-002', 'e0f76921-72f1-4df2-a3c3-61a7a10a7002', 'Rekayasa Perangkat Lunak', 'Prinsip SOLID, Clean Architecture, Design Patterns', NOW(), NOW()),
('SB-003', 'e0f76921-72f1-4df2-a3c3-61a7a10a7003', 'Pemasaran Digital', 'SEO, social media marketing, consumer analytics', NOW(), NOW()),
('SB-004', 'e0f76921-72f1-4df2-a3c3-61a7a10a7004', 'Tipografi & Tata Letak', 'Desain visual komunikasi dan estetika huruf', NOW(), NOW()),
('SB-005', 'e0f76921-72f1-4df2-a3c3-61a7a10a7005', 'Pengembangan Kebiasaan', 'Mindset, fokus, dan produktivitas', NOW(), NOW());

INSERT INTO `penulis` (`id_penulis`, `uuid`, `nama_penulis`, `deskripsi_penulis`, `created_at`, `updated_at`) VALUES
('PGN-001', 'e0f76921-72f1-4df2-a3c3-61a7a10a8001', 'Dr. Rian Hidayat, M.Kom.', 'Pakar algoritma dan komputasi cerdas', NOW(), NOW()),
('PGN-002', 'e0f76921-72f1-4df2-a3c3-61a7a10a8002', 'Prof. Dr. Hendra Gunawan, S.E., M.M.', 'Pakar ekonomi bisnis dan transformasi digital', NOW(), NOW()),
('PGN-003', 'e0f76921-72f1-4df2-a3c3-61a7a10a8003', 'Siti Nurhaliza, M.Ds.', 'Desainer grafis dan kurator tipografi', NOW(), NOW()),
('PGN-004', 'e0f76921-72f1-4df2-a3c3-61a7a10a8004', 'Robert C. Martin', 'Penulis Clean Code dan Clean Architecture', NOW(), NOW()),
('PGN-005', 'e0f76921-72f1-4df2-a3c3-61a7a10a8005', 'James Clear', 'Penulis bestseller Atomic Habits', NOW(), NOW()),
('PGN-006', 'e0f76921-72f1-4df2-a3c3-61a7a10a8006', 'Pramoedya Ananta Toer', 'Sastrawan legendaris Indonesia', NOW(), NOW());

INSERT INTO `penerbit` (`id_penerbit`, `uuid`, `nama_penerbit`, `deskripsi_penerbit`, `created_at`, `updated_at`) VALUES
('PN-001', 'e0f76921-72f1-4df2-a3c3-61a7a10a9001', 'Informatika Bandung', 'Penerbit buku teknologi komputasi nasional', NOW(), NOW()),
('PN-002', 'e0f76921-72f1-4df2-a3c3-61a7a10a9002', 'Penerbit Erlangga', 'Penerbit terkemuka buku pendidikan universitas', NOW(), NOW()),
('PN-003', 'e0f76921-72f1-4df2-a3c3-61a7a10a9003', 'Gramedia Pustaka Utama', 'Penerbit buku umum dan sains populer', NOW(), NOW()),
('PN-004', 'e0f76921-72f1-4df2-a3c3-61a7a10a9004', 'Prentice Hall', 'Penerbit buku referensi ilmu komputer internasional', NOW(), NOW()),
('PN-005', 'e0f76921-72f1-4df2-a3c3-61a7a10a9005', 'Lentera Dipantara', 'Penerbit karya sastra klasik', NOW(), NOW());

INSERT INTO `buku` (`id_buku`, `uuid`, `id_jenis_buku`, `id_kategori_buku`, `id_penerbit`, `judul_buku`, `isbn`, `tahun_terbit`, `edisi`, `deskripsi`, `sampul_buku`, `jumlah_halaman`, `created_at`, `updated_at`) VALUES
('BK-001', 'e0f76921-72f1-4df2-a3c3-61a7a10b1001', 'JB-001', 'KB-001', 'PN-001', 'Algoritma & Struktur Data dengan Python', '978-623-7131-01-2', 2024, 'Edisi Kedua', 'Pembahasan mendalam konsep struktur data dan implementasinya pada Python.', 'https://images.unsplash.com/photo-1532012164546-f432f2e3edd4?auto=format&fit=crop&w=600&q=80', 380, NOW(), NOW()),
('BK-002', 'e0f76921-72f1-4df2-a3c3-61a7a10b1002', 'JB-001', 'KB-002', 'PN-002', 'Manajemen Pemasaran Strategis di Era Digital', '978-602-444-889-1', 2023, 'Edisi Pertama', 'Panduan strategis manajemen brand omni-channel dan analitik konsumen modern.', 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80', 412, NOW(), NOW()),
('BK-003', 'e0f76921-72f1-4df2-a3c3-61a7a10b1003', 'JB-001', 'KB-003', 'PN-003', 'Prinsip Desain Komunikasi Visual & Tipografi', '978-602-03-9120-5', 2024, 'Edisi Revisi', 'Pedoman estetika tata huruf, grid system, dan perancangan komunikasi visual.', 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80', 296, NOW(), NOW()),
('BK-004', 'e0f76921-72f1-4df2-a3c3-61a7a10b1004', 'JB-002', 'KB-001', 'PN-004', 'Clean Architecture: A Craftsman\'s Guide to Software Structure', '978-013-449-416-6', 2018, '1st Edition', 'Fondasi arsitektur piranti lunak tangguh, modular, dan mudah diuji.', 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=600&q=80', 432, NOW(), NOW()),
('BK-005', 'e0f76921-72f1-4df2-a3c3-61a7a10b1005', 'JB-005', 'KB-005', 'PN-003', 'Atomic Habits: Perubahan Kecil yang Memberikan Hasil Luar Biasa', '978-602-06-3317-6', 2021, 'Cetakan ke-12', 'Strategi perubahan 1% setiap hari untuk mencapai hasil maksimal.', 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80', 356, NOW(), NOW()),
('BK-006', 'e0f76921-72f1-4df2-a3c3-61a7a10b1006', 'JB-005', 'KB-005', 'PN-005', 'Bumi Manusia', '978-979-97312-3-4', 2010, 'Edisi Khusus', 'Kisah klasik pergolakan sosial dan nasionalisme di Hindia Belanda.', 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80', 535, NOW(), NOW());

INSERT INTO `buku_penulis` (`id_buku_penulis`, `uuid`, `id_buku`, `id_penulis`, `created_at`, `updated_at`) VALUES
('BP-001', 'e0f76921-72f1-4df2-a3c3-61a7a10b2001', 'BK-001', 'PGN-001', NOW(), NOW()),
('BP-002', 'e0f76921-72f1-4df2-a3c3-61a7a10b2002', 'BK-002', 'PGN-002', NOW(), NOW()),
('BP-003', 'e0f76921-72f1-4df2-a3c3-61a7a10b2003', 'BK-003', 'PGN-003', NOW(), NOW()),
('BP-004', 'e0f76921-72f1-4df2-a3c3-61a7a10b2004', 'BK-004', 'PGN-004', NOW(), NOW()),
('BP-005', 'e0f76921-72f1-4df2-a3c3-61a7a10b2005', 'BK-005', 'PGN-005', NOW(), NOW()),
('BP-006', 'e0f76921-72f1-4df2-a3c3-61a7a10b2006', 'BK-006', 'PGN-006', NOW(), NOW());

INSERT INTO `buku_subjek` (`id_buku_subjek`, `uuid`, `id_buku`, `id_subjek_buku`, `created_at`, `updated_at`) VALUES
('BS-001', 'e0f76921-72f1-4df2-a3c3-61a7a10b3001', 'BK-001', 'SB-001', NOW(), NOW()),
('BS-002', 'e0f76921-72f1-4df2-a3c3-61a7a10b3002', 'BK-002', 'SB-003', NOW(), NOW()),
('BS-003', 'e0f76921-72f1-4df2-a3c3-61a7a10b3003', 'BK-003', 'SB-004', NOW(), NOW()),
('BS-004', 'e0f76921-72f1-4df2-a3c3-61a7a10b3004', 'BK-004', 'SB-002', NOW(), NOW()),
('BS-005', 'e0f76921-72f1-4df2-a3c3-61a7a10b3005', 'BK-005', 'SB-005', NOW(), NOW()),
('BS-006', 'e0f76921-72f1-4df2-a3c3-61a7a10b3006', 'BK-006', 'SB-005', NOW(), NOW());

INSERT INTO `eksemplar_buku` (`id_eksemplar`, `uuid`, `id_buku`, `kode_inventaris`, `nomor_panggil`, `lokasi_rak`, `status`, `created_at`, `updated_at`) VALUES
('EK-001', 'e0f76921-72f1-4df2-a3c3-61a7a10b4001', 'BK-001', 'INV-BK001-01', '005.1 HID a c.1', 'Lantai 2, Rak Komputer 03', 'Tersedia', NOW(), NOW()),
('EK-002', 'e0f76921-72f1-4df2-a3c3-61a7a10b4002', 'BK-001', 'INV-BK001-02', '005.1 HID a c.2', 'Lantai 2, Rak Komputer 03', 'Tersedia', NOW(), NOW()),
('EK-003', 'e0f76921-72f1-4df2-a3c3-61a7a10b4003', 'BK-001', 'INV-BK001-03', '005.1 HID a c.3', 'Lantai 2, Rak Komputer 03', 'Dipinjam', NOW(), NOW()),
('EK-004', 'e0f76921-72f1-4df2-a3c3-61a7a10b4004', 'BK-002', 'INV-BK002-01', '658.8 GUN m c.1', 'Lantai 2, Rak Ekonomi 01', 'Tersedia', NOW(), NOW()),
('EK-005', 'e0f76921-72f1-4df2-a3c3-61a7a10b4005', 'BK-003', 'INV-BK003-01', '741.6 NUR p c.1', 'Lantai 2, Rak Desain 02', 'Tersedia', NOW(), NOW()),
('EK-006', 'e0f76921-72f1-4df2-a3c3-61a7a10b4006', 'BK-004', 'INV-BK004-01', '005.12 MAR c c.1', 'Lantai 2, Rak Komputer 04', 'Tersedia', NOW(), NOW()),
('EK-007', 'e0f76921-72f1-4df2-a3c3-61a7a10b4007', 'BK-005', 'INV-BK005-01', '158.1 CLE a c.1', 'Lantai 1, Rak Umum 01', 'Tersedia', NOW(), NOW()),
('EK-008', 'e0f76921-72f1-4df2-a3c3-61a7a10b4008', 'BK-006', 'INV-BK006-01', '899.221 TOE b c.1', 'Lantai 1, Rak Sastra 02', 'Tersedia', NOW(), NOW());

INSERT INTO `kunjungan_buku` (`id_kunjungan_buku`, `uuid`, `id_buku`, `jumlah_kunjungan`, `created_at`, `updated_at`) VALUES
('KJB-001', 'e0f76921-72f1-4df2-a3c3-61a7a10b5001', 'BK-001', 128, NOW(), NOW()),
('KJB-002', 'e0f76921-72f1-4df2-a3c3-61a7a10b5002', 'BK-002', 94, NOW(), NOW()),
('KJB-003', 'e0f76921-72f1-4df2-a3c3-61a7a10b5003', 'BK-003', 87, NOW(), NOW()),
('KJB-004', 'e0f76921-72f1-4df2-a3c3-61a7a10b5004', 'BK-004', 210, NOW(), NOW()),
('KJB-005', 'e0f76921-72f1-4df2-a3c3-61a7a10b5005', 'BK-005', 342, NOW(), NOW()),
('KJB-006', 'e0f76921-72f1-4df2-a3c3-61a7a10b5006', 'BK-006', 176, NOW(), NOW());

INSERT INTO `berita` (`id_berita`, `uuid`, `id_pengguna`, `judul_berita`, `slug`, `kategori_berita`, `deskripsi_berita`, `isi_berita`, `gambar_berita`, `tanggal_publikasi`, `views_count`, `created_at`, `updated_at`) VALUES
('BR-001', 'e0f76921-72f1-4df2-a3c3-61a7a10b6001', 'PG-001', 'Peluncuran Layanan Akses Digital MNCU Library Versi Baru', 'peluncuran-layanan-akses-digital-mncu-library-versi-baru', 'Pengumuman', 'Perpustakaan MNC University meresmikan platform digital terintegrasi untuk katalog OPAC dan portal repository ilmiah.', 'Perpustakaan MNC University secara resmi meluncurkan platform digital terbaru yang mengintegrasikan layanan katalog OPAC, repository karya ilmiah, dan akses portal e-journal internasional.', 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=800&q=80', CURDATE() - INTERVAL 2 DAY, 142, NOW(), NOW()),
('BR-002', 'e0f76921-72f1-4df2-a3c3-61a7a10b6002', 'PG-002', 'Workshop Literasi Informasi & Pengutipan Sitasi Ilmiah dengan Zotero', 'workshop-literasi-informasi-dan-pengutipan-sitasi-ilmiah-dengan-zotero', 'Kegiatan', 'Tingkatkan kualitas penulisan tugas akhir Anda dengan manajemen sitasi otomatis menggunakan Zotero dan Mendeley.', 'Workshop literasi informasi ini bertujuan membekali mahasiswa semester akhir dengan keahlian teknis manajemen sitasi ilmiah, pencegahan plagiarisme, dan strategi pencarian paper bereputasi di Scopus dan DOAJ.', 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=800&q=80', CURDATE() - INTERVAL 5 DAY, 88, NOW(), NOW()),
('BR-003', 'e0f76921-72f1-4df2-a3c3-61a7a10b6003', 'PG-003', 'Penambahan 500+ Judul E-Book Terbaru Bidang Sains Data & AI', 'penambahan-500-judul-e-book-terbaru-bidang-sains-data-dan-ai', 'Koleksi', 'Koleksi e-book terbaru telah ditambahkan ke portal digital, mencakup deep learning, cloud computing, dan computer vision.', 'Dalam rangka mendukung pembelajaran riset modern, Perpustakaan MNC University menambahkan lebih dari 500 koleksi e-book internasional berlisensi resmi dari penerbit dunia seperti Springer, Wiley, dan IEEE.', 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=800&q=80', CURDATE() - INTERVAL 8 DAY, 205, NOW(), NOW());

INSERT INTO `acara_perpustakaan` (`id_acara`, `uuid`, `id_pengguna`, `nama_acara`, `slug`, `kategori_acara`, `ringkasan_acara`, `deskripsi_acara`, `gambar_acara`, `narasumber`, `tanggal_mulai`, `tanggal_selesai`, `lokasi_acara`, `tautan_pendaftaran`, `kuota`, `is_active`, `created_at`, `updated_at`) VALUES
('AC-001', 'e0f76921-72f1-4df2-a3c3-61a7a10b7001', 'PG-001', 'MNCU Library Book Fair & Tech Literacy Fest 2026', 'mncu-library-book-fair-tech-literacy-fest-2026', 'Festival', 'Pameran buku terakbar tahun ini dengan diskon menarik, talkshow penulis, dan demo AI tools.', 'Acara pameran buku tahunan MNC University menghadirkan puluhan penerbit terkemuka, pameran inovasi karya mahasiswa, dan seminar teknologi informasi untuk pendidikan.', 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', 'Dewi Lestari & Tim AI MNC Group', NOW() + INTERVAL 7 DAY, NOW() + INTERVAL 7 DAY + INTERVAL 7 HOUR, 'Auditorium Lantai 3, Gedung MNC University', 'https://bit.ly/mncu-bookfair-2026', 250, 1, NOW(), NOW()),
('AC-002', 'e0f76921-72f1-4df2-a3c3-61a7a10b7002', 'PG-002', 'Bedah Buku: Clean Architecture & Software Modern', 'bedah-buku-clean-architecture-software-modern', 'Bedah Buku', 'Membahas tuntas prinsip perancangan piranti lunak enterprise berskala besar bersama praktisi industri.', 'Sesi bedah buku Clean Architecture bersama Principal Engineer terkemuka. Terbuka bagi seluruh mahasiswa program studi Informatika dan Sistem Informasi.', 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=800&q=80', 'Rian Hidayat, M.Kom.', NOW() + INTERVAL 14 DAY, NOW() + INTERVAL 14 DAY + INTERVAL 3 HOUR, 'Ruang Multimedia Perpustakaan MNC University', 'https://bit.ly/bedah-buku-mncu', 80, 1, NOW(), NOW());

INSERT INTO `kontak` (`id_kontak`, `uuid`, `id_pengguna`, `nama`, `email_kontak`, `nomor_telepon`, `isi_pesan`, `waktu_kirim`, `created_at`, `updated_at`) VALUES
('KK-001', 'e0f76921-72f1-4df2-a3c3-61a7a10b8001', 'PG-004', 'Ahmad Fauzi Rahman', 'fauzi.mhs@mncu.ac.id', '081234567890', 'Mohon informasi prosedur perpanjangan masa pinjam buku skripsi melalui portal online.', NOW() - INTERVAL 1 DAY, NOW(), NOW());

INSERT INTO `log_kunjungan` (`id_log_kunjungan`, `uuid`, `id_pengguna`, `waktu_kunjungan`, `session_id`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
('LK-001', 'e0f76921-72f1-4df2-a3c3-61a7a10b9001', 'PG-004', NOW() - INTERVAL 2 HOUR, 'seed-session-1', '127.0.0.1', 'Mozilla/5.0 Seeder', NOW(), NOW()),
('LK-002', 'e0f76921-72f1-4df2-a3c3-61a7a10b9002', NULL, NOW() - INTERVAL 4 HOUR, 'seed-session-2', '127.0.0.1', 'Mozilla/5.0 Seeder', NOW(), NOW()),
('LK-003', 'e0f76921-72f1-4df2-a3c3-61a7a10b9003', NULL, NOW() - INTERVAL 6 HOUR, 'seed-session-3', '127.0.0.1', 'Mozilla/5.0 Seeder', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
