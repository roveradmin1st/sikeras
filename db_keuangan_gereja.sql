-- --------------------------------------------------------
-- SQL Dump untuk Database db_keuangan_gereja
-- Disesuaikan 100% dengan Draft Skripsi & Revisi Pembimbing (Multi-Tenant SaaS)
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `db_keuangan_gereja` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_keuangan_gereja`;

-- --------------------------------------------------------
-- 1. Table structure for table `churches` (Tenant / Data Gereja)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `churches` (
  `id_church` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_gereja` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `kontak` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif','trial') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_church`),
  UNIQUE KEY `churches_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Table structure for table `rayon`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rayon` (
  `id_rayon` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_church` bigint(20) unsigned NOT NULL,
  `nama_rayon` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_rayon`),
  KEY `rayon_id_church_idx` (`id_church`),
  CONSTRAINT `rayon_id_church_fk` FOREIGN KEY (`id_church`) REFERENCES `churches` (`id_church`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Table structure for table `jemaat`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jemaat` (
  `id_jemaat` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_church` bigint(20) unsigned NOT NULL,
  `nama_jemaat` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `id_rayon` bigint(20) unsigned NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_jemaat`),
  KEY `jemaat_id_church_idx` (`id_church`),
  KEY `jemaat_id_rayon_idx` (`id_rayon`),
  CONSTRAINT `jemaat_id_church_fk` FOREIGN KEY (`id_church`) REFERENCES `churches` (`id_church`) ON DELETE CASCADE,
  CONSTRAINT `jemaat_id_rayon_fk` FOREIGN KEY (`id_rayon`) REFERENCES `rayon` (`id_rayon`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_church` bigint(20) unsigned NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','bendahara_kas','bendahara_pembangunan','pendeta','jemaat') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `id_jemaat` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `users_church_username_unique` (`id_church`,`username`),
  KEY `users_id_church_idx` (`id_church`),
  KEY `users_id_jemaat_idx` (`id_jemaat`),
  CONSTRAINT `users_id_church_fk` FOREIGN KEY (`id_church`) REFERENCES `churches` (`id_church`) ON DELETE CASCADE,
  CONSTRAINT `users_id_jemaat_fk` FOREIGN KEY (`id_jemaat`) REFERENCES `jemaat` (`id_jemaat`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Table structure for table `kategori_transaksi`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kategori_transaksi` (
  `id_kategori` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_church` bigint(20) unsigned NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `jenis` enum('pemasukan','pengeluaran') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kategori`),
  KEY `kategori_id_church_idx` (`id_church`),
  CONSTRAINT `kategori_id_church_fk` FOREIGN KEY (`id_church`) REFERENCES `churches` (`id_church`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. Table structure for table `transaksi_kas`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `transaksi_kas` (
  `id_transaksi` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_church` bigint(20) unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kredit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `saldo` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jenis_kas` enum('kas_umum','pembangunan','rayon') NOT NULL,
  `id_kategori` bigint(20) unsigned NOT NULL,
  `id_user` bigint(20) unsigned NOT NULL,
  `id_jemaat` bigint(20) unsigned DEFAULT NULL,
  `bukti_transaksi` varchar(255) DEFAULT NULL,
  `status` enum('pending','disetujui') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `transaksi_id_church_idx` (`id_church`),
  KEY `transaksi_id_kategori_idx` (`id_kategori`),
  KEY `transaksi_id_user_idx` (`id_user`),
  KEY `transaksi_id_jemaat_idx` (`id_jemaat`),
  CONSTRAINT `transaksi_id_church_fk` FOREIGN KEY (`id_church`) REFERENCES `churches` (`id_church`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_id_kategori_fk` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_transaksi` (`id_kategori`) ON DELETE RESTRICT,
  CONSTRAINT `transaksi_id_user_fk` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT,
  CONSTRAINT `transaksi_id_jemaat_fk` FOREIGN KEY (`id_jemaat`) REFERENCES `jemaat` (`id_jemaat`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. Table structure for table `janji_iman`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `janji_iman` (
  `id_janji` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_church` bigint(20) unsigned NOT NULL,
  `id_jemaat` bigint(20) unsigned NOT NULL,
  `total_janji` decimal(15,2) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `status` enum('belum_lunas','lunas') NOT NULL DEFAULT 'belum_lunas',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_janji`),
  KEY `janji_id_church_idx` (`id_church`),
  KEY `janji_id_jemaat_idx` (`id_jemaat`),
  CONSTRAINT `janji_id_church_fk` FOREIGN KEY (`id_church`) REFERENCES `churches` (`id_church`) ON DELETE CASCADE,
  CONSTRAINT `janji_id_jemaat_fk` FOREIGN KEY (`id_jemaat`) REFERENCES `jemaat` (`id_jemaat`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 8. Table structure for table `pembayaran_janji`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pembayaran_janji` (
  `id_bayar` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_church` bigint(20) unsigned NOT NULL,
  `id_janji` bigint(20) unsigned NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `id_transaksi` bigint(20) unsigned DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `id_user` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_bayar`),
  KEY `pembayaran_id_church_idx` (`id_church`),
  KEY `pembayaran_id_janji_idx` (`id_janji`),
  KEY `pembayaran_id_transaksi_idx` (`id_transaksi`),
  KEY `pembayaran_id_user_idx` (`id_user`),
  CONSTRAINT `pembayaran_id_church_fk` FOREIGN KEY (`id_church`) REFERENCES `churches` (`id_church`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_id_janji_fk` FOREIGN KEY (`id_janji`) REFERENCES `janji_iman` (`id_janji`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_id_transaksi_fk` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi_kas` (`id_transaksi`) ON DELETE SET NULL,
  CONSTRAINT `pembayaran_id_user_fk` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 9. Table structure for table `laporan`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `laporan` (
  `id_laporan` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_church` bigint(20) unsigned NOT NULL,
  `kode_laporan` varchar(255) NOT NULL,
  `jenis_kas` enum('kas_umum','pembangunan') NOT NULL,
  `periode_awal` date NOT NULL,
  `periode_akhir` date NOT NULL,
  `total_debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_kredit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `saldo_akhir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `id_user` bigint(20) unsigned NOT NULL,
  `status` enum('pending','disetujui') NOT NULL DEFAULT 'pending',
  `tanggal_diajukan` datetime NOT NULL,
  `tanggal_disetujui` datetime DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_laporan`),
  KEY `laporan_id_church_idx` (`id_church`),
  KEY `laporan_id_user_idx` (`id_user`),
  CONSTRAINT `laporan_id_church_fk` FOREIGN KEY (`id_church`) REFERENCES `churches` (`id_church`) ON DELETE CASCADE,
  CONSTRAINT `laporan_id_user_fk` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 10. Table structure for table `approval` (Konfirmasi Validasi Pendeta)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `approval` (
  `id_approval` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_church` bigint(20) unsigned NOT NULL,
  `id_transaksi` bigint(20) unsigned NOT NULL,
  `id_pendeta` bigint(20) unsigned NOT NULL,
  `tanggal_approve` datetime NOT NULL,
  `status` enum('disetujui','ditolak') NOT NULL DEFAULT 'disetujui',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_approval`),
  KEY `approval_id_church_idx` (`id_church`),
  KEY `approval_id_transaksi_idx` (`id_transaksi`),
  KEY `approval_id_pendeta_idx` (`id_pendeta`),
  CONSTRAINT `approval_id_church_fk` FOREIGN KEY (`id_church`) REFERENCES `churches` (`id_church`) ON DELETE CASCADE,
  CONSTRAINT `approval_id_transaksi_fk` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi_kas` (`id_transaksi`) ON DELETE CASCADE,
  CONSTRAINT `approval_id_pendeta_fk` FOREIGN KEY (`id_pendeta`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
