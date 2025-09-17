-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for dbarsipp
DROP DATABASE IF EXISTS `dbarsipp`;
CREATE DATABASE IF NOT EXISTS `dbarsipp` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dbarsipp`;

-- Dumping structure for table dbarsipp.tbl_arsip
DROP TABLE IF EXISTS `tbl_arsip`;
CREATE TABLE IF NOT EXISTS `tbl_arsip` (
  `id_arsip` int NOT NULL AUTO_INCREMENT,
  `no_surat` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_surat` date NOT NULL,
  `tanggal_diterima` date DEFAULT NULL,
  `perihal` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `id_departemen` int NOT NULL,
  `id_pengirim` int NOT NULL,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id_arsip`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table dbarsipp.tbl_arsip: ~4 rows (approximately)
DELETE FROM `tbl_arsip`;
INSERT INTO `tbl_arsip` (`id_arsip`, `no_surat`, `tanggal_surat`, `tanggal_diterima`, `perihal`, `status`, `id_departemen`, `id_pengirim`, `file`, `created_at`) VALUES
	(9, '2025/XIIV/3444', '2025-01-02', '2025-07-10', 'Promosi', '1', 2, 2, '679369f316e51.jpg', '2025-01-10'),
	(10, '2024/III/004', '2024-11-27', '2025-07-10', 'Bazaar UMKM', '1', 1, 2, '67792137df210.png', '2025-01-10'),
	(11, '2024/III/004', '2025-01-01', '2025-01-02', 'Sosialisasi', '1', 1, 4, '', '2025-02-10'),
	(24, '2025/III/008', '2019-01-06', '2025-02-06', 'Masalah Keuangan', '1', 6, 2, '67a3ed6873306.jpg', '2025-02-10');

-- Dumping structure for table dbarsipp.tbl_arsip_keluar
DROP TABLE IF EXISTS `tbl_arsip_keluar`;
CREATE TABLE IF NOT EXISTS `tbl_arsip_keluar` (
  `id_arsip_keluar` int NOT NULL AUTO_INCREMENT,
  `no_surat` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_surat` date NOT NULL,
  `tanggal_diterima` date DEFAULT NULL,
  `tujuan_surat` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_tujuan` int NOT NULL,
  `perihal` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `id_departemen` int NOT NULL,
  `id_pengirim` int NOT NULL,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_kirim` date DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id_arsip_keluar`),
  KEY `id_departemen` (`id_departemen`),
  KEY `id_pengirim` (`id_pengirim`),
  KEY `id_tujuan` (`id_tujuan`),
  CONSTRAINT `tbl_arsip_keluar_ibfk_1` FOREIGN KEY (`id_departemen`) REFERENCES `tbl_departemen` (`id_departemen`) ON DELETE RESTRICT,
  CONSTRAINT `tbl_arsip_keluar_ibfk_2` FOREIGN KEY (`id_pengirim`) REFERENCES `tbl_pegawai` (`id_pegawai`) ON DELETE RESTRICT,
  CONSTRAINT `tbl_arsip_keluar_ibfk_3` FOREIGN KEY (`id_tujuan`) REFERENCES `tbl_pengirim_surat` (`id_pengirim`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table dbarsipp.tbl_arsip_keluar: ~0 rows (approximately)
DELETE FROM `tbl_arsip_keluar`;
INSERT INTO `tbl_arsip_keluar` (`id_arsip_keluar`, `no_surat`, `tanggal_surat`, `tanggal_diterima`, `tujuan_surat`, `id_tujuan`, `perihal`, `status`, `id_departemen`, `id_pengirim`, `file`, `tanggal_kirim`, `created_at`) VALUES
	(2, '2025/XIIV/344/KLR', '2025-07-14', '2025-07-14', 'Penugas keluar kota Banjarmasin', 3, 'Rapat Daerah', '1', 4, 2, '1752503152_cuci makan.jpg', '2025-07-14', '2025-07-14');

-- Dumping structure for table dbarsipp.tbl_barang
DROP TABLE IF EXISTS `tbl_barang`;
CREATE TABLE IF NOT EXISTS `tbl_barang` (
  `id_barang` int NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_barang` enum('habis_pakai','tidak_habis_pakai') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `id_departemen` int NOT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id_barang`),
  KEY `id_departemen` (`id_departemen`),
  CONSTRAINT `tbl_barang_ibfk_1` FOREIGN KEY (`id_departemen`) REFERENCES `tbl_departemen` (`id_departemen`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table dbarsipp.tbl_barang: ~6 rows (approximately)
DELETE FROM `tbl_barang`;
INSERT INTO `tbl_barang` (`id_barang`, `nama_barang`, `jenis_barang`, `deskripsi`, `id_departemen`, `created_at`) VALUES
	(1, 'Kertas A4', 'habis_pakai', 'Kertas A4 80gsm', 1, '2025-07-03'),
	(2, 'Meja Kerja', 'tidak_habis_pakai', 'Meja kerja kayu', 2, '2025-07-03'),
	(3, 'Tinta Printer', 'habis_pakai', 'Tinta hitam untuk printer HP', 6, '2025-07-03'),
	(4, 'Komputer Desktop', 'tidak_habis_pakai', 'PC Intel i5, RAM 8GB', 4, '2025-07-03'),
	(7, 'spidol', 'habis_pakai', 'test', 4, '2025-07-03'),
	(8, 'penggaris', 'tidak_habis_pakai', 'test', 6, '2025-07-03');

-- Dumping structure for table dbarsipp.tbl_departemen
DROP TABLE IF EXISTS `tbl_departemen`;
CREATE TABLE IF NOT EXISTS `tbl_departemen` (
  `id_departemen` int NOT NULL AUTO_INCREMENT,
  `nama_departemen` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id_departemen`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table dbarsipp.tbl_departemen: ~4 rows (approximately)
DELETE FROM `tbl_departemen`;
INSERT INTO `tbl_departemen` (`id_departemen`, `nama_departemen`, `created_at`) VALUES
	(1, 'Kepegawaian', '2025-02-10'),
	(2, 'Sekretariat', '2025-02-10'),
	(4, 'PPDN', '2025-01-10'),
	(6, 'Keuangan', '2025-01-10');

-- Dumping structure for table dbarsipp.tbl_pegawai
DROP TABLE IF EXISTS `tbl_pegawai`;
CREATE TABLE IF NOT EXISTS `tbl_pegawai` (
  `id_pegawai` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `departemen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id_pegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table dbarsipp.tbl_pegawai: ~3 rows (approximately)
DELETE FROM `tbl_pegawai`;
INSERT INTO `tbl_pegawai` (`id_pegawai`, `nama`, `nip`, `jabatan`, `departemen`, `jenis_kelamin`, `created_at`) VALUES
	(1, 'Taufiqurrahman, SE', '197107272008011011', 'Penata Tk. I', '1', 'L', '2025-01-10'),
	(2, 'H. Hasan, SKM, MM', '196907221990031006', 'Kepala Dinas', '4', 'L', '2025-02-10'),
	(3, 'Jadri, SE', '196909191989031005', 'Kasubag Keuangan', '6', 'L', '2025-03-10');

-- Dumping structure for table dbarsipp.tbl_pengirim_surat
DROP TABLE IF EXISTS `tbl_pengirim_surat`;
CREATE TABLE IF NOT EXISTS `tbl_pengirim_surat` (
  `id_pengirim` int NOT NULL AUTO_INCREMENT,
  `nama_pengirim` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id_pengirim`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table dbarsipp.tbl_pengirim_surat: ~3 rows (approximately)
DELETE FROM `tbl_pengirim_surat`;
INSERT INTO `tbl_pengirim_surat` (`id_pengirim`, `nama_pengirim`, `alamat`, `no_hp`, `email`, `created_at`) VALUES
	(2, 'Gramedia', 'Jalan Veteran', '05113456273', 'robianoor2002@gmail.com', '2025-01-10'),
	(3, 'Rumah Sakit Ansari Saleh', 'Jalan Brigjen Hasan Basri', '05117653202', 'rsansarisaleh@gmail.com', '2025-01-10'),
	(4, 'Dinas Kelautan dan Perikanan Provinsi Kalimantan S', 'Jl. Jenderal Sudirman No.9, Loktabat Utara, Kec. Banjarbaru Utara, Kota Banjar Baru', '05114772037', 'dkp.provkalsel@gmail.com', '2025-02-10');

-- Dumping structure for table dbarsipp.tbl_status_barang
DROP TABLE IF EXISTS `tbl_status_barang`;
CREATE TABLE IF NOT EXISTS `tbl_status_barang` (
  `id_status` int NOT NULL AUTO_INCREMENT,
  `id_barang` int NOT NULL,
  `status` enum('tersedia','dipinjam','rusak') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_pegawai` int DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id_status`),
  KEY `id_barang` (`id_barang`),
  KEY `id_pegawai` (`id_pegawai`),
  CONSTRAINT `tbl_status_barang_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `tbl_barang` (`id_barang`) ON DELETE CASCADE,
  CONSTRAINT `tbl_status_barang_ibfk_2` FOREIGN KEY (`id_pegawai`) REFERENCES `tbl_pegawai` (`id_pegawai`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table dbarsipp.tbl_status_barang: ~4 rows (approximately)
DELETE FROM `tbl_status_barang`;
INSERT INTO `tbl_status_barang` (`id_status`, `id_barang`, `status`, `id_pegawai`, `keterangan`, `created_at`) VALUES
	(1, 2, 'tersedia', NULL, 'Meja tersedia di ruang sekretariat', '2025-07-03'),
	(2, 4, 'dipinjam', 1, 'PC dipinjam untuk presentasi', '2025-07-04'),
	(3, 4, 'rusak', NULL, 'PC rusak, perlu perbaikan', '2025-07-05'),
	(5, 8, 'rusak', NULL, 'barang rusak', '2025-07-03');

-- Dumping structure for table dbarsipp.tbl_stok_barang
DROP TABLE IF EXISTS `tbl_stok_barang`;
CREATE TABLE IF NOT EXISTS `tbl_stok_barang` (
  `id_stok` int NOT NULL AUTO_INCREMENT,
  `id_barang` int NOT NULL,
  `jumlah` int NOT NULL,
  `tipe_transaksi` enum('masuk','keluar') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `id_pegawai` int DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id_stok`),
  KEY `id_barang` (`id_barang`),
  KEY `id_pegawai` (`id_pegawai`),
  CONSTRAINT `tbl_stok_barang_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `tbl_barang` (`id_barang`) ON DELETE CASCADE,
  CONSTRAINT `tbl_stok_barang_ibfk_2` FOREIGN KEY (`id_pegawai`) REFERENCES `tbl_pegawai` (`id_pegawai`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table dbarsipp.tbl_stok_barang: ~4 rows (approximately)
DELETE FROM `tbl_stok_barang`;
INSERT INTO `tbl_stok_barang` (`id_stok`, `id_barang`, `jumlah`, `tipe_transaksi`, `keterangan`, `id_pegawai`, `created_at`) VALUES
	(1, 1, 100, 'masuk', 'Pembelian kertas A4', 1, '2025-07-03'),
	(2, 1, 20, 'keluar', 'Penggunaan untuk laporan', 3, '2025-07-04'),
	(3, 3, 5, 'masuk', 'Pembelian tinta printer', 2, '2025-07-03'),
	(6, 7, 15, 'masuk', 'dibeli anang', 3, '2025-07-03');

-- Dumping structure for table dbarsipp.tbl_user
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table dbarsipp.tbl_user: ~3 rows (approximately)
DELETE FROM `tbl_user`;
INSERT INTO `tbl_user` (`id_user`, `username`, `password`) VALUES
	(1, 'sekretariat', '827ccb0eea8a706c4c34a16891f84e7b'),
	(2, 'kadisdag', '827ccb0eea8a706c4c34a16891f84e7b'),
	(3, 'kepegawaian', '827ccb0eea8a706c4c34a16891f84e7b');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
