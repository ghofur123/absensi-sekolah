-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table absensi_sekolah.absensis
CREATE TABLE IF NOT EXISTS `absensis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned DEFAULT NULL,
  `jadwal_id` bigint unsigned DEFAULT NULL,
  `diabsenkan_oleh_user_id` bigint unsigned NOT NULL,
  `status` enum('hadir','izin','sakit','alpa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'alpa',
  `waktu_scan` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absensis_jadwal_id_foreign` (`jadwal_id`),
  KEY `absensis_diabsenkan_oleh_user_id_foreign` (`diabsenkan_oleh_user_id`),
  KEY `absensis_siswa_id_jadwal_id_index` (`siswa_id`,`jadwal_id`),
  CONSTRAINT `absensis_diabsenkan_oleh_user_id_foreign` FOREIGN KEY (`diabsenkan_oleh_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensis_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE SET NULL,
  CONSTRAINT `absensis_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=242 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.absensis: ~0 rows (approximately)
INSERT INTO `absensis` (`id`, `siswa_id`, `jadwal_id`, `diabsenkan_oleh_user_id`, `status`, `waktu_scan`, `created_at`, `updated_at`) VALUES
	(241, 61, 1, 2, 'hadir', '2026-01-24 02:07:42', '2026-01-24 02:07:42', '2026-01-24 02:07:42');

-- Dumping structure for table absensi_sekolah.absensi_gurus
CREATE TABLE IF NOT EXISTS `absensi_gurus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lembaga_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `jadwal_id` bigint unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu_scan` datetime DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `jarak_meter` int DEFAULT NULL,
  `radius_valid` tinyint(1) NOT NULL DEFAULT '0',
  `metode` enum('qr','manual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'qr',
  `status` enum('hadir','izin','sakit','alpha') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `status_masuk` enum('tepat_waktu','terlambat') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `absensi_gurus_lembaga_id_guru_id_tanggal_unique` (`lembaga_id`,`guru_id`,`tanggal`),
  KEY `absensi_gurus_guru_id_foreign` (`guru_id`),
  KEY `absensi_gurus_jadwal_id_foreign` (`jadwal_id`),
  CONSTRAINT `absensi_gurus_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_gurus_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE SET NULL,
  CONSTRAINT `absensi_gurus_lembaga_id_foreign` FOREIGN KEY (`lembaga_id`) REFERENCES `lembagas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.absensi_gurus: ~2 rows (approximately)
INSERT INTO `absensi_gurus` (`id`, `lembaga_id`, `guru_id`, `jadwal_id`, `tanggal`, `waktu_scan`, `latitude`, `longitude`, `jarak_meter`, `radius_valid`, `metode`, `status`, `status_masuk`, `keterangan`, `created_at`, `updated_at`) VALUES
	(14, 2, 1, 29, '2026-01-23', '2026-01-23 10:52:58', -8.13370560, 113.80669254, 1, 1, 'qr', 'hadir', 'terlambat', 'Terlambat 3 jam 37 menit (scan 10:52)', '2026-01-22 20:52:59', '2026-01-22 20:52:59'),
	(15, 1, 1, 5, '2026-01-23', '2026-01-23 10:58:54', -8.13369444, 113.80669609, 0, 1, 'qr', 'hadir', 'terlambat', 'Terlambat 1 jam 43 menit (scan 10:58)', '2026-01-22 20:58:54', '2026-01-22 20:58:54'),
	(16, 1, 1, 1, '2026-01-24', '2026-01-24 09:18:09', -8.10715734, 113.86396800, 0, 1, 'qr', 'hadir', 'terlambat', 'Terlambat 24.7329064 menit (scan 09:18)', '2026-01-24 02:18:09', '2026-01-24 02:18:09');

-- Dumping structure for table absensi_sekolah.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.cache: ~5 rows (approximately)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1769029566),
	('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1769029566;', 1769029566),
	('laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1769219917),
	('laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1769219917;', 1769219917),
	('laravel-cache-spatie.permission.cache', 'a:3:{s:5:"alias";a:4:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";}s:11:"permissions";a:102:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:12:"view_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:"a";i:2;s:1:"b";s:16:"view_any_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:"a";i:3;s:1:"b";s:14:"create_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:"a";i:4;s:1:"b";s:14:"update_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:"a";i:5;s:1:"b";s:15:"restore_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:"a";i:6;s:1:"b";s:19:"restore_any_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:"a";i:7;s:1:"b";s:17:"replicate_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:"a";i:8;s:1:"b";s:15:"reorder_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:"a";i:9;s:1:"b";s:14:"delete_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:"a";i:10;s:1:"b";s:18:"delete_any_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:"a";i:11;s:1:"b";s:20:"force_delete_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:"a";i:12;s:1:"b";s:24:"force_delete_any_absensi";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:"a";i:13;s:1:"b";s:9:"view_guru";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:"a";i:14;s:1:"b";s:13:"view_any_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:14;a:4:{s:1:"a";i:15;s:1:"b";s:11:"create_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:15;a:4:{s:1:"a";i:16;s:1:"b";s:11:"update_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:16;a:4:{s:1:"a";i:17;s:1:"b";s:12:"restore_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:17;a:4:{s:1:"a";i:18;s:1:"b";s:16:"restore_any_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:18;a:4:{s:1:"a";i:19;s:1:"b";s:14:"replicate_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:19;a:4:{s:1:"a";i:20;s:1:"b";s:12:"reorder_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:20;a:4:{s:1:"a";i:21;s:1:"b";s:11:"delete_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:21;a:4:{s:1:"a";i:22;s:1:"b";s:15:"delete_any_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:22;a:4:{s:1:"a";i:23;s:1:"b";s:17:"force_delete_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:23;a:4:{s:1:"a";i:24;s:1:"b";s:21:"force_delete_any_guru";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:24;a:4:{s:1:"a";i:25;s:1:"b";s:11:"view_jadwal";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:25;a:4:{s:1:"a";i:26;s:1:"b";s:15:"view_any_jadwal";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:26;a:4:{s:1:"a";i:27;s:1:"b";s:13:"create_jadwal";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:27;a:4:{s:1:"a";i:28;s:1:"b";s:13:"update_jadwal";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:28;a:4:{s:1:"a";i:29;s:1:"b";s:14:"restore_jadwal";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:29;a:4:{s:1:"a";i:30;s:1:"b";s:18:"restore_any_jadwal";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:30;a:4:{s:1:"a";i:31;s:1:"b";s:16:"replicate_jadwal";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:31;a:4:{s:1:"a";i:32;s:1:"b";s:14:"reorder_jadwal";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:32;a:4:{s:1:"a";i:33;s:1:"b";s:13:"delete_jadwal";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:33;a:4:{s:1:"a";i:34;s:1:"b";s:17:"delete_any_jadwal";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:34;a:4:{s:1:"a";i:35;s:1:"b";s:19:"force_delete_jadwal";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:35;a:4:{s:1:"a";i:36;s:1:"b";s:23:"force_delete_any_jadwal";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:36;a:4:{s:1:"a";i:37;s:1:"b";s:10:"view_kelas";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:37;a:4:{s:1:"a";i:38;s:1:"b";s:14:"view_any_kelas";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:38;a:4:{s:1:"a";i:39;s:1:"b";s:12:"create_kelas";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:39;a:4:{s:1:"a";i:40;s:1:"b";s:12:"update_kelas";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:40;a:4:{s:1:"a";i:41;s:1:"b";s:13:"restore_kelas";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:41;a:4:{s:1:"a";i:42;s:1:"b";s:17:"restore_any_kelas";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:42;a:4:{s:1:"a";i:43;s:1:"b";s:15:"replicate_kelas";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:43;a:4:{s:1:"a";i:44;s:1:"b";s:13:"reorder_kelas";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:44;a:4:{s:1:"a";i:45;s:1:"b";s:12:"delete_kelas";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:45;a:4:{s:1:"a";i:46;s:1:"b";s:16:"delete_any_kelas";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:46;a:4:{s:1:"a";i:47;s:1:"b";s:18:"force_delete_kelas";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:47;a:4:{s:1:"a";i:48;s:1:"b";s:22:"force_delete_any_kelas";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:48;a:4:{s:1:"a";i:49;s:1:"b";s:12:"view_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:49;a:4:{s:1:"a";i:50;s:1:"b";s:16:"view_any_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:50;a:4:{s:1:"a";i:51;s:1:"b";s:14:"create_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:51;a:4:{s:1:"a";i:52;s:1:"b";s:14:"update_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:52;a:4:{s:1:"a";i:53;s:1:"b";s:15:"restore_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:53;a:4:{s:1:"a";i:54;s:1:"b";s:19:"restore_any_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:54;a:4:{s:1:"a";i:55;s:1:"b";s:17:"replicate_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:55;a:4:{s:1:"a";i:56;s:1:"b";s:15:"reorder_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:56;a:4:{s:1:"a";i:57;s:1:"b";s:14:"delete_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:57;a:4:{s:1:"a";i:58;s:1:"b";s:18:"delete_any_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:58;a:4:{s:1:"a";i:59;s:1:"b";s:20:"force_delete_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:59;a:4:{s:1:"a";i:60;s:1:"b";s:24:"force_delete_any_lembaga";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:60;a:4:{s:1:"a";i:61;s:1:"b";s:20:"view_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:61;a:4:{s:1:"a";i:62;s:1:"b";s:24:"view_any_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:62;a:4:{s:1:"a";i:63;s:1:"b";s:22:"create_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:63;a:4:{s:1:"a";i:64;s:1:"b";s:22:"update_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:64;a:4:{s:1:"a";i:65;s:1:"b";s:23:"restore_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:65;a:4:{s:1:"a";i:66;s:1:"b";s:27:"restore_any_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:66;a:4:{s:1:"a";i:67;s:1:"b";s:25:"replicate_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:67;a:4:{s:1:"a";i:68;s:1:"b";s:23:"reorder_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:68;a:4:{s:1:"a";i:69;s:1:"b";s:22:"delete_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:69;a:4:{s:1:"a";i:70;s:1:"b";s:26:"delete_any_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:70;a:4:{s:1:"a";i:71;s:1:"b";s:28:"force_delete_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:71;a:4:{s:1:"a";i:72;s:1:"b";s:32:"force_delete_any_mata::pelajaran";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:72;a:4:{s:1:"a";i:73;s:1:"b";s:9:"view_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:73;a:4:{s:1:"a";i:74;s:1:"b";s:13:"view_any_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:74;a:4:{s:1:"a";i:75;s:1:"b";s:11:"create_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:75;a:4:{s:1:"a";i:76;s:1:"b";s:11:"update_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:76;a:4:{s:1:"a";i:77;s:1:"b";s:11:"delete_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:77;a:4:{s:1:"a";i:78;s:1:"b";s:15:"delete_any_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:78;a:4:{s:1:"a";i:79;s:1:"b";s:10:"view_siswa";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:79;a:4:{s:1:"a";i:80;s:1:"b";s:14:"view_any_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:80;a:4:{s:1:"a";i:81;s:1:"b";s:12:"create_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:81;a:4:{s:1:"a";i:82;s:1:"b";s:12:"update_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:82;a:4:{s:1:"a";i:83;s:1:"b";s:13:"restore_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:83;a:4:{s:1:"a";i:84;s:1:"b";s:17:"restore_any_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:84;a:4:{s:1:"a";i:85;s:1:"b";s:15:"replicate_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:85;a:4:{s:1:"a";i:86;s:1:"b";s:13:"reorder_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:86;a:4:{s:1:"a";i:87;s:1:"b";s:12:"delete_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:87;a:4:{s:1:"a";i:88;s:1:"b";s:16:"delete_any_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:88;a:4:{s:1:"a";i:89;s:1:"b";s:18:"force_delete_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:89;a:4:{s:1:"a";i:90;s:1:"b";s:22:"force_delete_any_siswa";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:90;a:4:{s:1:"a";i:91;s:1:"b";s:9:"view_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:91;a:4:{s:1:"a";i:92;s:1:"b";s:13:"view_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:92;a:4:{s:1:"a";i:93;s:1:"b";s:11:"create_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:93;a:4:{s:1:"a";i:94;s:1:"b";s:11:"update_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:94;a:4:{s:1:"a";i:95;s:1:"b";s:12:"restore_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:95;a:4:{s:1:"a";i:96;s:1:"b";s:16:"restore_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:96;a:4:{s:1:"a";i:97;s:1:"b";s:14:"replicate_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:97;a:4:{s:1:"a";i:98;s:1:"b";s:12:"reorder_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:98;a:4:{s:1:"a";i:99;s:1:"b";s:11:"delete_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:99;a:4:{s:1:"a";i:100;s:1:"b";s:15:"delete_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:100;a:4:{s:1:"a";i:101;s:1:"b";s:17:"force_delete_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:101;a:4:{s:1:"a";i:102;s:1:"b";s:21:"force_delete_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}}s:5:"roles";a:2:{i:0;a:3:{s:1:"a";i:1;s:1:"b";s:11:"super_admin";s:1:"c";s:3:"web";}i:1;a:3:{s:1:"a";i:2;s:1:"b";s:4:"Guru";s:1:"c";s:3:"web";}}}', 1769302931);

-- Dumping structure for table absensi_sekolah.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.cache_locks: ~0 rows (approximately)

-- Dumping structure for table absensi_sekolah.failed_import_rows
CREATE TABLE IF NOT EXISTS `failed_import_rows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `import_id` bigint unsigned NOT NULL,
  `data` json NOT NULL,
  `validation_errors` json DEFAULT NULL,
  `error` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `failed_import_rows_import_id_foreign` (`import_id`),
  CONSTRAINT `failed_import_rows_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.failed_import_rows: ~0 rows (approximately)

-- Dumping structure for table absensi_sekolah.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table absensi_sekolah.gurus
CREATE TABLE IF NOT EXISTS `gurus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lembaga_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gurus_lembaga_id_nik_unique` (`lembaga_id`,`nik`),
  KEY `gurus_user_id_foreign` (`user_id`),
  CONSTRAINT `gurus_lembaga_id_foreign` FOREIGN KEY (`lembaga_id`) REFERENCES `lembagas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gurus_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.gurus: ~10 rows (approximately)
INSERT INTO `gurus` (`id`, `lembaga_id`, `user_id`, `nama`, `nik`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 'Radika Gara Salahudin', '1970307087', '2026-01-20 23:50:37', '2026-01-21 14:47:30'),
	(2, 1, 3, 'Laras Widiastuti S.E.', '1970039042', '2026-01-20 23:50:37', '2026-01-21 14:55:17'),
	(3, 1, NULL, 'Gamanto Habibi', '1970913316', '2026-01-20 23:50:37', '2026-01-20 23:50:37'),
	(4, 1, NULL, 'Wadi Uwais', '1970814445', '2026-01-20 23:50:37', '2026-01-20 23:50:37'),
	(5, 1, NULL, 'Viktor Muni Nababan M.Pd', '1970249024', '2026-01-20 23:50:37', '2026-01-20 23:50:37'),
	(6, 2, NULL, 'Darmana Maryadi Gunawan', '1970950235', '2026-01-20 23:50:37', '2026-01-20 23:50:37'),
	(7, 2, 5, 'Winda Mandasari', '1970825969', '2026-01-20 23:50:37', '2026-01-24 01:58:01'),
	(8, 2, NULL, 'Lala Puspita M.Pd', '1970117243', '2026-01-20 23:50:37', '2026-01-20 23:50:37'),
	(9, 2, NULL, 'Okta Najmudin', '1970488655', '2026-01-20 23:50:37', '2026-01-20 23:50:37'),
	(10, 2, NULL, 'Bala Nugroho', '1970647551', '2026-01-20 23:50:37', '2026-01-20 23:50:37'),
	(11, 1, 4, 'jhkhj', 'hjkhj', '2026-01-21 20:54:36', '2026-01-21 20:55:29');

-- Dumping structure for table absensi_sekolah.imports
CREATE TABLE IF NOT EXISTS `imports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `importer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_rows` int unsigned NOT NULL DEFAULT '0',
  `processed_rows` int unsigned NOT NULL DEFAULT '0',
  `imported_rows` int unsigned NOT NULL DEFAULT '0',
  `failed_rows` int unsigned NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imports_user_id_foreign` (`user_id`),
  CONSTRAINT `imports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.imports: ~0 rows (approximately)

-- Dumping structure for table absensi_sekolah.jadwals
CREATE TABLE IF NOT EXISTS `jadwals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lembaga_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `mata_pelajaran_id` bigint unsigned NOT NULL,
  `hari` json NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwals_lembaga_id_foreign` (`lembaga_id`),
  KEY `jadwals_guru_id_foreign` (`guru_id`),
  KEY `jadwals_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  CONSTRAINT `jadwals_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwals_lembaga_id_foreign` FOREIGN KEY (`lembaga_id`) REFERENCES `lembagas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwals_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajarans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.jadwals: ~1 rows (approximately)
INSERT INTO `jadwals` (`id`, `lembaga_id`, `guru_id`, `mata_pelajaran_id`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 1, '["senin", "selasa", "rabu", "kamis", "jumat", "sabtu"]', '08:38:26', '09:38:29', '2026-01-24 01:38:34', '2026-01-24 01:38:34'),
	(2, 2, 7, 2, '["senin", "selasa", "rabu", "sabtu", "jumat", "kamis"]', '08:00:00', '09:00:00', '2026-01-24 01:58:42', '2026-01-24 01:58:42');

-- Dumping structure for table absensi_sekolah.jadwal_kelas
CREATE TABLE IF NOT EXISTS `jadwal_kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jadwal_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jadwal_kelas_jadwal_id_kelas_id_unique` (`jadwal_id`,`kelas_id`),
  KEY `jadwal_kelas_jadwal_id_index` (`jadwal_id`),
  KEY `jadwal_kelas_kelas_id_index` (`kelas_id`),
  CONSTRAINT `jadwal_kelas_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.jadwal_kelas: ~6 rows (approximately)
INSERT INTO `jadwal_kelas` (`id`, `jadwal_id`, `kelas_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, NULL, NULL),
	(3, 1, 3, NULL, NULL),
	(5, 2, 1, NULL, NULL),
	(6, 2, 2, NULL, NULL),
	(7, 2, 3, NULL, NULL);

-- Dumping structure for table absensi_sekolah.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.jobs: ~0 rows (approximately)

-- Dumping structure for table absensi_sekolah.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.job_batches: ~0 rows (approximately)

-- Dumping structure for table absensi_sekolah.kelas
CREATE TABLE IF NOT EXISTS `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lembaga_id` bigint unsigned NOT NULL,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelas_lembaga_id_foreign` (`lembaga_id`),
  CONSTRAINT `kelas_lembaga_id_foreign` FOREIGN KEY (`lembaga_id`) REFERENCES `lembagas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.kelas: ~6 rows (approximately)
INSERT INTO `kelas` (`id`, `lembaga_id`, `nama_kelas`, `tingkat`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Kelas 7', '7', '2026-01-20 23:50:37', '2026-01-21 13:48:52'),
	(2, 1, 'Kelas 8', '8', '2026-01-20 23:50:37', '2026-01-21 13:49:06'),
	(3, 1, 'Kelas 9', '9', '2026-01-20 23:50:37', '2026-01-21 13:49:18'),
	(4, 2, 'Kelas 10', '10', '2026-01-20 23:50:37', '2026-01-21 13:49:34'),
	(5, 2, 'Kelas 11', '11', '2026-01-20 23:50:37', '2026-01-21 13:50:56'),
	(6, 2, 'Kelas 12', '12', '2026-01-20 23:50:37', '2026-01-21 13:52:53');

-- Dumping structure for table absensi_sekolah.lembagas
CREATE TABLE IF NOT EXISTS `lembagas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_lembaga` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `latitude` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `radius_meter` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.lembagas: ~3 rows (approximately)
INSERT INTO `lembagas` (`id`, `nama_lembaga`, `alamat`, `latitude`, `longitude`, `radius_meter`, `created_at`, `updated_at`) VALUES
	(1, 'MTS NURUL MANNAN', 'Jl. Pasar Jum at NO 1', '-8.1071573420074', '113.863968', '100', '2026-01-20 23:50:37', '2026-01-24 02:17:56'),
	(2, 'SMK NURUL MANNAN', 'Jl. Pasar Jum at NO 2', '-8.1336978048189', '113.80669845597', '100', '2026-01-20 23:50:37', '2026-01-21 21:53:08'),
	(3, 'sss', 'sss', '-8.1337017859221', '113.80669618753', '100', '2026-01-21 21:52:22', '2026-01-21 23:16:03');

-- Dumping structure for table absensi_sekolah.mata_pelajarans
CREATE TABLE IF NOT EXISTS `mata_pelajarans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lembaga_id` bigint unsigned NOT NULL,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mata_pelajarans_lembaga_id_foreign` (`lembaga_id`),
  CONSTRAINT `mata_pelajarans_lembaga_id_foreign` FOREIGN KEY (`lembaga_id`) REFERENCES `lembagas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.mata_pelajarans: ~2 rows (approximately)
INSERT INTO `mata_pelajarans` (`id`, `lembaga_id`, `nama_mapel`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Dhuha', '2026-01-20 23:50:37', '2026-01-20 23:50:37'),
	(2, 2, 'Dhuha', '2026-01-20 23:50:37', '2026-01-20 23:50:37');

-- Dumping structure for table absensi_sekolah.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.migrations: ~14 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_05_20_121526_create_imports_table', 1),
	(5, '2025_05_20_121527_create_failed_import_rows_table', 1),
	(6, '2025_07_23_233104_create_permission_tables', 1),
	(7, '2026_01_18_214754_create_lembagas_table', 1),
	(8, '2026_01_18_220429_create_kelas_table', 1),
	(9, '2026_01_18_221006_create_mata_pelajarans_table', 1),
	(10, '2026_01_18_222037_create_siswas_table', 1),
	(11, '2026_01_19_020733_create_gurus_table', 1),
	(13, '2026_01_19_131846_create_absensis_table', 1),
	(14, '2026_01_22_052812_create_absensi_gurus_table', 2),
	(16, '2026_01_23_060238_create_jadwal_kelas_table', 3),
	(17, '2026_01_19_022537_create_jadwals_table', 4);

-- Dumping structure for table absensi_sekolah.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table absensi_sekolah.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.model_has_roles: ~3 rows (approximately)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(2, 'App\\Models\\User', 3),
	(2, 'App\\Models\\User', 4),
	(2, 'App\\Models\\User', 5);

-- Dumping structure for table absensi_sekolah.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table absensi_sekolah.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.permissions: ~102 rows (approximately)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(2, 'view_any_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(3, 'create_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(4, 'update_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(5, 'restore_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(6, 'restore_any_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(7, 'replicate_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(8, 'reorder_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(9, 'delete_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(10, 'delete_any_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(11, 'force_delete_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(12, 'force_delete_any_absensi', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(13, 'view_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(14, 'view_any_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(15, 'create_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(16, 'update_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(17, 'restore_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(18, 'restore_any_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(19, 'replicate_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(20, 'reorder_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(21, 'delete_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(22, 'delete_any_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(23, 'force_delete_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(24, 'force_delete_any_guru', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(25, 'view_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(26, 'view_any_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(27, 'create_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(28, 'update_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(29, 'restore_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(30, 'restore_any_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(31, 'replicate_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(32, 'reorder_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(33, 'delete_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(34, 'delete_any_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(35, 'force_delete_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(36, 'force_delete_any_jadwal', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(37, 'view_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(38, 'view_any_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(39, 'create_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(40, 'update_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(41, 'restore_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(42, 'restore_any_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(43, 'replicate_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(44, 'reorder_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(45, 'delete_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(46, 'delete_any_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(47, 'force_delete_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(48, 'force_delete_any_kelas', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(49, 'view_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(50, 'view_any_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(51, 'create_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(52, 'update_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(53, 'restore_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(54, 'restore_any_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(55, 'replicate_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(56, 'reorder_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(57, 'delete_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(58, 'delete_any_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(59, 'force_delete_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(60, 'force_delete_any_lembaga', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(61, 'view_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(62, 'view_any_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(63, 'create_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(64, 'update_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(65, 'restore_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(66, 'restore_any_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(67, 'replicate_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(68, 'reorder_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(69, 'delete_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(70, 'delete_any_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(71, 'force_delete_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(72, 'force_delete_any_mata::pelajaran', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(73, 'view_role', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(74, 'view_any_role', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(75, 'create_role', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(76, 'update_role', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(77, 'delete_role', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(78, 'delete_any_role', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(79, 'view_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(80, 'view_any_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(81, 'create_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(82, 'update_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(83, 'restore_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(84, 'restore_any_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(85, 'replicate_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(86, 'reorder_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(87, 'delete_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(88, 'delete_any_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(89, 'force_delete_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(90, 'force_delete_any_siswa', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(91, 'view_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(92, 'view_any_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(93, 'create_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(94, 'update_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(95, 'restore_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(96, 'restore_any_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(97, 'replicate_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(98, 'reorder_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(99, 'delete_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(100, 'delete_any_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(101, 'force_delete_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03'),
	(102, 'force_delete_any_user', 'web', '2026-01-20 23:52:03', '2026-01-20 23:52:03');

-- Dumping structure for table absensi_sekolah.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.roles: ~2 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'super_admin', 'web', '2026-01-20 23:51:52', '2026-01-20 23:51:52'),
	(2, 'Guru', 'web', '2026-01-21 00:23:09', '2026-01-21 00:23:09');

-- Dumping structure for table absensi_sekolah.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.role_has_permissions: ~121 rows (approximately)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(17, 1),
	(18, 1),
	(19, 1),
	(20, 1),
	(21, 1),
	(22, 1),
	(23, 1),
	(24, 1),
	(25, 1),
	(26, 1),
	(27, 1),
	(28, 1),
	(29, 1),
	(30, 1),
	(31, 1),
	(32, 1),
	(33, 1),
	(34, 1),
	(35, 1),
	(36, 1),
	(37, 1),
	(38, 1),
	(39, 1),
	(40, 1),
	(41, 1),
	(42, 1),
	(43, 1),
	(44, 1),
	(45, 1),
	(46, 1),
	(47, 1),
	(48, 1),
	(49, 1),
	(50, 1),
	(51, 1),
	(52, 1),
	(53, 1),
	(54, 1),
	(55, 1),
	(56, 1),
	(57, 1),
	(58, 1),
	(59, 1),
	(60, 1),
	(61, 1),
	(62, 1),
	(63, 1),
	(64, 1),
	(65, 1),
	(66, 1),
	(67, 1),
	(68, 1),
	(69, 1),
	(70, 1),
	(71, 1),
	(72, 1),
	(73, 1),
	(74, 1),
	(75, 1),
	(76, 1),
	(77, 1),
	(78, 1),
	(79, 1),
	(80, 1),
	(81, 1),
	(82, 1),
	(83, 1),
	(84, 1),
	(85, 1),
	(86, 1),
	(87, 1),
	(88, 1),
	(89, 1),
	(90, 1),
	(91, 1),
	(92, 1),
	(93, 1),
	(94, 1),
	(95, 1),
	(96, 1),
	(97, 1),
	(98, 1),
	(99, 1),
	(100, 1),
	(101, 1),
	(102, 1),
	(1, 2),
	(2, 2),
	(3, 2),
	(4, 2),
	(5, 2),
	(6, 2),
	(7, 2),
	(8, 2),
	(9, 2),
	(10, 2),
	(11, 2),
	(12, 2),
	(13, 2),
	(25, 2),
	(26, 2),
	(37, 2),
	(38, 2),
	(61, 2),
	(79, 2);

-- Dumping structure for table absensi_sekolah.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.sessions: ~2 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('Gm3cQ5MqXYT3ssfeYm29073IoOV12lJwQGtvnR7O', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiOExtYWNCMFFFUHJKTnd5QUZnUzNuMjlpSHF4dDRnMG82dHpXZWFpWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9qYWR3YWxzIjtzOjU6InJvdXRlIjtzOjM4OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuamFkd2Fscy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImZhZDNjYmRmYTlmOTcxYmU0YmNhMmRhYjQ4YjI2ZTE3ZWExM2RlZWZjZDg1MWRkNDE3MGJjYWU3NmJkNzdkMDYiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=', 1769221163),
	('y2oGp1DFLPGXSyuo92GhagJBnh8yxnO5jaIuAMSr', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiRURVQ284aEszWjZsRkEySDE3NDFINzZyNFNMSHZDOXZIaU5NQ01wQSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vbGVtYmFnYXMvMS9lZGl0IjtzOjU6InJvdXRlIjtzOjM4OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMubGVtYmFnYXMuZWRpdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6IjgwNWU5MWUyOWY5OTY2ZDk0MTQ2ODRhZTBkNzVkNTdmNDk1ZTg2NThlYTE3NDIyZjMxNWEzYmRkZjM3NDdjMGYiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=', 1769221088);

-- Dumping structure for table absensi_sekolah.siswas
CREATE TABLE IF NOT EXISTS `siswas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lembaga_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_siswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `status` enum('aktif','lulus','pindah') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `no_wa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `siswas_lembaga_id_foreign` (`lembaga_id`),
  KEY `siswas_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `siswas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `siswas_lembaga_id_foreign` FOREIGN KEY (`lembaga_id`) REFERENCES `lembagas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=231 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.siswas: ~170 rows (approximately)
INSERT INTO `siswas` (`id`, `lembaga_id`, `kelas_id`, `nisn`, `nama_siswa`, `jenis_kelamin`, `alamat`, `status`, `no_wa`, `created_at`, `updated_at`) VALUES
	(61, 1, 1, '1234', 'Ahmad Fadil', 'Laki-laki', 'jln ledokombo', 'aktif', '1234567', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(62, 1, 1, '1235', 'Almira Azaria Adibah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234568', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(63, 1, 1, '1236', 'Alya Rahma Az Zahra', 'Laki-laki', 'jln ledokombo', 'aktif', '1234569', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(64, 1, 1, '1237', 'Arfa Izzul Haq', 'Laki-laki', 'jln ledokombo', 'aktif', '1234570', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(65, 1, 1, '1238', 'Ayik Aprilia', 'Laki-laki', 'jln ledokombo', 'aktif', '1234571', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(66, 1, 1, '1239', 'Erika Maulida Januar', 'Laki-laki', 'jln ledokombo', 'aktif', '1234572', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(67, 1, 1, '1240', 'Felicia Dwi Putri Arifina', 'Laki-laki', 'jln ledokombo', 'aktif', '1234573', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(68, 1, 1, '1241', 'Imtisal Amrillah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234574', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(69, 1, 1, '1242', 'Isna Ilma Rama Dani', 'Laki-laki', 'jln ledokombo', 'aktif', '1234575', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(70, 1, 1, '1243', 'Jihan Aura Ramadhani', 'Laki-laki', 'jln ledokombo', 'aktif', '1234576', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(71, 1, 1, '1244', 'Muhammad Farhan Firmansyah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234577', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(72, 1, 1, '1245', 'Muhammad Rafa Rama Saputra', 'Laki-laki', 'jln ledokombo', 'aktif', '1234578', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(73, 1, 1, '1246', 'Maulana Malik Ibrahim', 'Laki-laki', 'jln ledokombo', 'aktif', '1234579', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(74, 1, 1, '1247', 'Miftahu Dzatil Hikmah Al Asyifa', 'Laki-laki', 'jln ledokombo', 'aktif', '1234580', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(75, 1, 1, '1248', 'Moh Syaiful Bahri', 'Laki-laki', 'jln ledokombo', 'aktif', '1234581', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(76, 1, 1, '1249', 'Muhammad Abdul Wakil', 'Laki-laki', 'jln ledokombo', 'aktif', '1234582', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(77, 1, 1, '1250', 'Muhammad Arif Maulana Fahreza', 'Laki-laki', 'jln ledokombo', 'aktif', '1234583', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(78, 1, 1, '1251', 'Muhammad Ayyubi Hayatul Qulub', 'Laki-laki', 'jln ledokombo', 'aktif', '1234584', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(79, 1, 1, '1252', 'Muhammad Ifan Fajriyanto', 'Laki-laki', 'jln ledokombo', 'aktif', '1234585', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(80, 1, 1, '1253', 'Muhammad Ifan Mubarok', 'Laki-laki', 'jln ledokombo', 'aktif', '1234586', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(81, 1, 1, '1254', 'Muhammad Rafelo Domitri Rizki', 'Laki-laki', 'jln ledokombo', 'aktif', '1234587', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(82, 1, 1, '1255', 'Muhammad Ridwan Alif', 'Laki-laki', 'jln ledokombo', 'aktif', '1234588', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(83, 1, 1, '1256', 'Muhammad Yasir Fahmi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234589', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(84, 1, 1, '1257', 'Putri Ameliana Az Zahro', 'Laki-laki', 'jln ledokombo', 'aktif', '1234590', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(85, 1, 1, '1258', 'Putri Arafah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234591', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(86, 1, 1, '1259', 'Rahel Nurvita Sari', 'Laki-laki', 'jln ledokombo', 'aktif', '1234592', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(87, 1, 1, '1260', 'Salsabella Diana Putri', 'Laki-laki', 'jln ledokombo', 'aktif', '1234593', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(88, 1, 1, '1261', 'Shakira Latifa', 'Laki-laki', 'jln ledokombo', 'aktif', '1234594', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(89, 1, 1, '1262', 'Shinta Nuriyah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234595', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(90, 1, 1, '1263', 'Syafika Wilda Maulidiyah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234596', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(91, 1, 1, '1264', 'Syifa Anggraeni', 'Laki-laki', 'jln ledokombo', 'aktif', '1234597', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(92, 1, 1, '1265', 'Widiya Ifrotul Kamila', 'Laki-laki', 'jln ledokombo', 'aktif', '1234598', '2026-01-21 13:53:34', '2026-01-21 13:53:34'),
	(93, 1, 2, '1265', 'Abdul Waris', 'Laki-laki', 'jln ledokombo', 'aktif', '1234599', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(94, 1, 2, '1265', 'Ardista Ifa Azzahro', 'Laki-laki', 'jln ledokombo', 'aktif', '1234600', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(95, 1, 2, '1265', 'Dewi Finza Anggraeni', 'Laki-laki', 'jln ledokombo', 'aktif', '1234601', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(96, 1, 2, '1265', 'Ibrahim Abdillah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234602', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(97, 1, 2, '1265', 'Muhammad Abbadi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234603', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(98, 1, 2, '1265', 'Muhammad Afif Ramadhani', 'Laki-laki', 'jln ledokombo', 'aktif', '1234604', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(99, 1, 2, '1265', 'Muhammad Arif Ramadhani', 'Laki-laki', 'jln ledokombo', 'aktif', '1234605', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(100, 1, 2, '1265', 'Muhammad Aril', 'Laki-laki', 'jln ledokombo', 'aktif', '1234606', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(101, 1, 2, '1265', 'Muhammad Roni Firdaus', 'Laki-laki', 'jln ledokombo', 'aktif', '1234607', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(102, 1, 2, '1265', 'Muhammad Saifudin', 'Laki-laki', 'jln ledokombo', 'aktif', '1234608', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(103, 1, 2, '1265', 'Maulidi Nisfillail', 'Laki-laki', 'jln ledokombo', 'aktif', '1234609', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(104, 1, 2, '1265', 'Medina Islami Firdausi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234610', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(105, 1, 2, '1265', 'Misbahul Munir', 'Laki-laki', 'jln ledokombo', 'aktif', '1234611', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(106, 1, 2, '1265', 'Moh Fajril Ramadhan', 'Laki-laki', 'jln ledokombo', 'aktif', '1234612', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(107, 1, 2, '1265', 'Muhammad Kafa Arjuna Putra', 'Laki-laki', 'jln ledokombo', 'aktif', '1234613', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(108, 1, 2, '1265', 'Muhammad Radil', 'Laki-laki', 'jln ledokombo', 'aktif', '1234614', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(109, 1, 2, '1265', 'Nafisah Izzatun Nihayah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234615', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(110, 1, 2, '1265', 'Nailatul Faizah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234616', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(111, 1, 2, '1265', 'Novita Sari', 'Laki-laki', 'jln ledokombo', 'aktif', '1234617', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(112, 1, 2, '1265', 'Nur Anisatul Husna', 'Laki-laki', 'jln ledokombo', 'aktif', '1234618', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(113, 1, 2, '1265', 'Rosa Putri Eka Rahayu', 'Laki-laki', 'jln ledokombo', 'aktif', '1234619', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(114, 1, 2, '1265', 'Siti Arina Safa', 'Laki-laki', 'jln ledokombo', 'aktif', '1234620', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(115, 1, 2, '1265', 'Siti Fina Wulandari', 'Laki-laki', 'jln ledokombo', 'aktif', '1234621', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(116, 1, 2, '1265', 'Siti Mabruroh', 'Laki-laki', 'jln ledokombo', 'aktif', '1234622', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(117, 1, 2, '1265', 'Siti Naylatul Maghfiroh', 'Laki-laki', 'jln ledokombo', 'aktif', '1234623', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(118, 1, 2, '1265', 'Sitti Lailatul Rosyadah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234624', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(119, 1, 2, '1265', 'Walidatul Masruroh', 'Laki-laki', 'jln ledokombo', 'aktif', '1234625', '2026-01-21 13:55:44', '2026-01-21 13:55:44'),
	(120, 1, 3, '1265', 'Abdul Fatah Ramadhan', 'Laki-laki', 'jln ledokombo', 'aktif', '1234626', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(121, 1, 3, '1265', 'Ahmad Mustofa Nabil', 'Laki-laki', 'jln ledokombo', 'aktif', '1234627', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(122, 1, 3, '1265', 'Amelia Madinatul Izza', 'Laki-laki', 'jln ledokombo', 'aktif', '1234628', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(123, 1, 3, '1265', 'Lena Ambarwati', 'Laki-laki', 'jln ledokombo', 'aktif', '1234629', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(124, 1, 3, '1265', 'Leni Rahmawati', 'Laki-laki', 'jln ledokombo', 'aktif', '1234630', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(125, 1, 3, '1265', 'Muhammad Lutfan Maulana Ibrahim', 'Laki-laki', 'jln ledokombo', 'aktif', '1234631', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(126, 1, 3, '1265', 'Muhammad Anwar Ramdani', 'Laki-laki', 'jln ledokombo', 'aktif', '1234632', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(127, 1, 3, '1265', 'Muhammad Imron Alfarisi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234633', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(128, 1, 3, '1265', 'Nia Ikromil Putri', 'Laki-laki', 'jln ledokombo', 'aktif', '1234634', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(129, 1, 3, '1265', 'Rista Zahratul Jannah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234635', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(130, 1, 3, '1265', 'Sakinatul Qubro', 'Laki-laki', 'jln ledokombo', 'aktif', '1234636', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(131, 1, 3, '1265', 'Shella Desvitha Putri', 'Laki-laki', 'jln ledokombo', 'aktif', '1234637', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(132, 1, 3, '1265', 'Siti Nadirotul Kutziyeh', 'Laki-laki', 'jln ledokombo', 'aktif', '1234638', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(133, 1, 3, '1265', 'Siti Nasriyah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234639', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(134, 1, 3, '1265', 'Sri Wahyuningsih', 'Laki-laki', 'jln ledokombo', 'aktif', '1234640', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(135, 1, 3, '1265', 'Syifatus Zehro', 'Laki-laki', 'jln ledokombo', 'aktif', '1234641', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(136, 1, 3, '1265', 'Wardatus Solehah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234642', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(137, 1, 3, '1265', 'Wulandari', 'Laki-laki', 'jln ledokombo', 'aktif', '1234643', '2026-01-21 13:57:16', '2026-01-21 13:57:16'),
	(138, 2, 4, '1265', 'Iwan Yuhandra', 'Laki-laki', 'jln ledokombo', 'aktif', '1234644', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(139, 2, 4, '1265', 'Muhammad Aril Riyadi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234645', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(140, 2, 4, '1265', 'Ainun Kafi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234646', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(141, 2, 4, '1265', 'Anindita Ulin Nafisatul Aini', 'Laki-laki', 'jln ledokombo', 'aktif', '1234647', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(142, 2, 4, '1265', 'Ervina Ayu Diastutik', 'Laki-laki', 'jln ledokombo', 'aktif', '1234648', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(143, 2, 4, '1265', 'Lukluatun Nurin Nafisah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234649', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(144, 2, 4, '1265', 'Maisaroh', 'Laki-laki', 'jln ledokombo', 'aktif', '1234650', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(145, 2, 4, '1265', 'Muhammad Alfiansyah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234651', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(146, 2, 4, '1265', 'Muhammad Faril', 'Laki-laki', 'jln ledokombo', 'aktif', '1234652', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(147, 2, 4, '1265', 'Muhammad Sulfa', 'Laki-laki', 'jln ledokombo', 'aktif', '1234653', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(148, 2, 4, '1265', 'Muhammad Syafiq Farihzaki', 'Laki-laki', 'jln ledokombo', 'aktif', '1234654', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(149, 2, 4, '1265', 'Nuril Umaydilla', 'Laki-laki', 'jln ledokombo', 'aktif', '1234655', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(150, 2, 4, '1265', 'Ridho Agung Pratama', 'Laki-laki', 'jln ledokombo', 'aktif', '1234656', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(151, 2, 4, '1265', 'Sinta Nur Aini', 'Laki-laki', 'jln ledokombo', 'aktif', '1234657', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(152, 2, 4, '1265', 'Aldi Anzah Daud', 'Laki-laki', 'jln ledokombo', 'aktif', '1234658', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(153, 2, 4, '1265', 'Achmad Fahriyanto', 'Laki-laki', 'jln ledokombo', 'aktif', '1234659', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(154, 2, 4, '1265', 'Ahmad Firdi Imtias', 'Laki-laki', 'jln ledokombo', 'aktif', '1234660', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(155, 2, 4, '1265', 'Alfin Abdillah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234661', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(156, 2, 4, '1265', 'Alvian', 'Laki-laki', 'jln ledokombo', 'aktif', '1234662', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(157, 2, 4, '1265', 'Cantika Lutfiana Dewi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234663', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(158, 2, 4, '1265', 'Erlin', 'Laki-laki', 'jln ledokombo', 'aktif', '1234664', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(159, 2, 4, '1265', 'Lailatul Badriah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234665', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(160, 2, 4, '1265', 'Lefi Maufiroh', 'Laki-laki', 'jln ledokombo', 'aktif', '1234666', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(161, 2, 4, '1265', 'Muhammad Abdul Wafi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234667', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(162, 2, 4, '1265', 'Muhammad Hotib Imbron', 'Laki-laki', 'jln ledokombo', 'aktif', '1234668', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(163, 2, 4, '1265', 'Muhammad Wakil', 'Laki-laki', 'jln ledokombo', 'aktif', '1234669', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(164, 2, 4, '1265', 'Muhammad Noval Arifefendi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234670', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(165, 2, 4, '1265', 'Muhammad Ilyas', 'Laki-laki', 'jln ledokombo', 'aktif', '1234671', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(166, 2, 4, '1265', 'Muhammad Kamil Kasi Saura', 'Laki-laki', 'jln ledokombo', 'aktif', '1234672', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(167, 2, 4, '1265', 'Nafisatul Islami', 'Laki-laki', 'jln ledokombo', 'aktif', '1234673', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(168, 2, 4, '1265', 'Qurratul Uyun', 'Laki-laki', 'jln ledokombo', 'aktif', '1234674', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(169, 2, 4, '1265', 'Sheilatul Hikmah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234675', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(170, 2, 4, '1265', 'Siti Barorotul Uyun', 'Laki-laki', 'jln ledokombo', 'aktif', '1234676', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(171, 2, 4, '1265', 'Ufil Ma\'arij', 'Laki-laki', 'jln ledokombo', 'aktif', '1234677', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(172, 2, 4, '1265', 'Wasilatul Hasanah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234678', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(173, 2, 4, '1265', 'Adonia Khanza Abida', 'Laki-laki', 'jln ledokombo', 'aktif', '1234679', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(174, 2, 4, '1265', 'Zuhro Aulia', 'Laki-laki', 'jln ledokombo', 'aktif', '1234680', '2026-01-21 14:00:23', '2026-01-21 14:00:23'),
	(175, 2, 5, '1266', 'Abdurahman Sofian', 'Laki-laki', 'jln ledokombo', 'aktif', '1234681', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(176, 2, 5, '1267', 'Alfatihatil Jannah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234682', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(177, 2, 5, '1268', 'Ali Wufron', 'Laki-laki', 'jln ledokombo', 'aktif', '1234683', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(178, 2, 5, '1269', 'Anggun Laela Sagita', 'Laki-laki', 'jln ledokombo', 'aktif', '1234684', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(179, 2, 5, '1270', 'Anisatul Jamilah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234685', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(180, 2, 5, '1271', 'Arina Novita Sari', 'Laki-laki', 'jln ledokombo', 'aktif', '1234686', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(181, 2, 5, '1272', 'Arini Aini Raudatul Jannah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234687', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(182, 2, 5, '1273', 'As\'adi Baisuni', 'Laki-laki', 'jln ledokombo', 'aktif', '1234688', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(183, 2, 5, '1274', 'Finatus Sa\'diyah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234689', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(184, 2, 5, '1275', 'Indah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234690', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(185, 2, 5, '1276', 'Lailatul Qomariah.', 'Laki-laki', 'jln ledokombo', 'aktif', '1234691', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(186, 2, 5, '1277', 'Moh. Syamsul Arifin', 'Laki-laki', 'jln ledokombo', 'aktif', '1234692', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(187, 2, 5, '1278', 'Muhammad Al Ayyubi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234693', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(188, 2, 5, '1279', 'Muhammad Beni Abdullah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234694', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(189, 2, 5, '1280', 'Muhammad Iksan', 'Laki-laki', 'jln ledokombo', 'aktif', '1234695', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(190, 2, 5, '1281', 'Muhammad Waliyul Hakim', 'Laki-laki', 'jln ledokombo', 'aktif', '1234696', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(191, 2, 5, '1282', 'Nabilatul Hasanah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234697', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(192, 2, 5, '1283', 'Nuraini', 'Laki-laki', 'jln ledokombo', 'aktif', '1234698', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(193, 2, 5, '1284', 'Nurul Aulia', 'Laki-laki', 'jln ledokombo', 'aktif', '1234699', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(194, 2, 5, '1285', 'Rini Hidati', 'Laki-laki', 'jln ledokombo', 'aktif', '1234700', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(195, 2, 5, '1286', 'Zaskia Dewi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234701', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(196, 2, 5, '1287', 'Zumrotin Nailatul Asyifa', 'Laki-laki', 'jln ledokombo', 'aktif', '1234702', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(197, 2, 5, '1288', 'Muhammad Muzammil', 'Laki-laki', 'jln ledokombo', 'aktif', '1234703', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(198, 2, 5, '1289', 'Afina Hairun Nisa', 'Laki-laki', 'jln ledokombo', 'aktif', '1234704', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(199, 2, 5, '1290', 'Dewi Fajri', 'Laki-laki', 'jln ledokombo', 'aktif', '1234705', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(200, 2, 5, '1291', 'Fatimatus Zahro', 'Laki-laki', 'jln ledokombo', 'aktif', '1234706', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(201, 2, 5, '1292', 'Ikhwansyah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234707', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(202, 2, 5, '1293', 'Muhammad Abdul Ghafur', 'Laki-laki', 'jln ledokombo', 'aktif', '1234708', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(203, 2, 5, '1294', 'Muhammad Aminullah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234709', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(204, 2, 5, '1295', 'Naimatur Romadhani', 'Laki-laki', 'jln ledokombo', 'aktif', '1234710', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(205, 2, 5, '1296', 'Nor Kamila', 'Laki-laki', 'jln ledokombo', 'aktif', '1234711', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(206, 2, 5, '1297', 'Sofia Nur Aini', 'Laki-laki', 'jln ledokombo', 'aktif', '1234712', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(207, 2, 5, '1298', 'Yayik', 'Laki-laki', 'jln ledokombo', 'aktif', '1234713', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(208, 2, 5, '1299', 'Zulfa Nurul Aishy', 'Laki-laki', 'jln ledokombo', 'aktif', '1234714', '2026-01-21 14:03:08', '2026-01-21 14:03:08'),
	(209, 2, 6, '1300', 'Fabian Fahri R.', 'Laki-laki', 'jln ledokombo', 'aktif', '1234715', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(210, 2, 6, '1301', 'Muhaimim', 'Laki-laki', 'jln ledokombo', 'aktif', '1234716', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(211, 2, 6, '1302', 'Muhammad Dodik', 'Laki-laki', 'jln ledokombo', 'aktif', '1234717', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(212, 2, 6, '1303', 'Muhammad Roni', 'Laki-laki', 'jln ledokombo', 'aktif', '1234718', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(213, 2, 6, '1304', 'Siti Afiana', 'Laki-laki', 'jln ledokombo', 'aktif', '1234719', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(214, 2, 6, '1305', 'Siti Aisyatur Rodiyeh', 'Laki-laki', 'jln ledokombo', 'aktif', '1234720', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(215, 2, 6, '1306', 'Siti Muwatdah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234721', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(216, 2, 6, '1307', 'Sitti Holimatus Zahro', 'Laki-laki', 'jln ledokombo', 'aktif', '1234722', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(217, 2, 6, '1308', 'Wafil Lukman', 'Laki-laki', 'jln ledokombo', 'aktif', '1234723', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(218, 2, 6, '1309', 'Arifatul Isnaini', 'Laki-laki', 'jln ledokombo', 'aktif', '1234724', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(219, 2, 6, '1310', 'Diana Rodiatus Sofiah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234725', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(220, 2, 6, '1311', 'Dina Ramadani', 'Laki-laki', 'jln ledokombo', 'aktif', '1234726', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(221, 2, 6, '1312', 'Dinatul Hasanah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234727', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(222, 2, 6, '1313', 'Dinda Putri Dewi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234728', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(223, 2, 6, '1314', 'Dwi Anita Sari', 'Laki-laki', 'jln ledokombo', 'aktif', '1234729', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(224, 2, 6, '1315', 'Koramah Nandini', 'Laki-laki', 'jln ledokombo', 'aktif', '1234730', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(225, 2, 6, '1316', 'Lailatul Hasanah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234731', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(226, 2, 6, '1317', 'Mutmainnatun Nafsiyah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234732', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(227, 2, 6, '1318', 'Ratihatul Mutahharoh', 'Laki-laki', 'jln ledokombo', 'aktif', '1234733', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(228, 2, 6, '1319', 'Septia Chaerunnisa', 'Laki-laki', 'jln ledokombo', 'aktif', '1234734', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(229, 2, 6, '1320', 'Siti Nur Latifah', 'Laki-laki', 'jln ledokombo', 'aktif', '1234735', '2026-01-21 14:05:17', '2026-01-21 14:05:17'),
	(230, 2, 6, '1321', 'Muhammad Hanafi', 'Laki-laki', 'jln ledokombo', 'aktif', '1234736', '2026-01-21 14:05:17', '2026-01-21 14:05:17');

-- Dumping structure for table absensi_sekolah.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_sekolah.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'abdul gafur', 'gafur.strik@gmail.com', NULL, '$2y$12$WqCtvCyb9yafql8aI3KmXuW14aoTL78Q9oXTMAnNR3ITbYiv0lShm', NULL, '2026-01-20 23:51:24', '2026-01-20 23:51:24'),
	(2, 'Radika Gara Salahudin', 'radikagarasalahudin@gmail.com', NULL, '$2y$12$GjKI49pvj5igeA6vq4a6v.vIj66qwUUuhzogIICFqk7iWDAS9A.2W', NULL, '2026-01-21 14:47:29', '2026-01-21 14:47:29'),
	(3, 'Laras Widiastuti S.E.', 'laraswidiastutis.e.@gmail.com', NULL, '$2y$12$4/Nz3VLihFC5SxAd.enDvOrlexCe9V4mfdpza7qyqEEMO7FhqTaPK', NULL, '2026-01-21 14:55:17', '2026-01-21 14:55:17'),
	(4, 'jhkhj', 'jhkhj@gmail.com', NULL, '$2y$12$2HAwVze9Fx3SdIspHNt.rODxFNFlH7bPfuE.YmbdJXmB9To8khcXC', NULL, '2026-01-21 20:55:29', '2026-01-21 20:55:29'),
	(5, 'Winda Mandasari', 'windamandasari@gmail.com', NULL, '$2y$12$oCnciDPILxoExCSnCuTTGu6TAXswJHuUK21noZqYqy6oca2PCwrEm', NULL, '2026-01-24 01:58:01', '2026-01-24 01:58:01');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
